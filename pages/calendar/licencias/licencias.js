// import * as dnls from '../dnls.js';
// import * as guardias from '../guardias/guardias.js';
import * as utils from '../utils.js'

// Calendar instantiation
var calendar;

// ========================================================================================================================================================
// MANEJO DE Registro de licencias
// ========================================================================================================================================================
/***************************************************************************************
 * Subscripcion a lo actualizacion de eventos
 * @param {Evento[]} eventos - todos los eventos del calendario
 * @author MVGP
 ****************************************************************************************/
const eventsUpdated = eventos => {
    const eventosLicencias = eventos.filter(e => e.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_LICENCIAS);
    createTableLicencias('tbLicencia', eventosLicencias);
}

const createTableLicencias = (id, eventos) => {

    let tbLicencia = $(`#${id}`);
    tbLicencia.DataTable().clear().destroy();
    tbLicencia.DataTable({
        "paging": false,
        "deferRender": true,
        "data": eventos,
        "columns": [
            { data: "id" },
            { data: "icon", render: (data, type, row) => `<i title="${row.subtipo_desc}" class="fa fa-${data}"></i>` },
            { data: "fecha_inicio", render: data => moment(data).format('DD/MM/YYYY') },
            { data: "fecha_fin", render: data => moment(data).format('DD/MM/YYYY') },
            {
                data: "",
                render: (data, type, row) => {
                    const mInicio = moment(row.fecha_inicio);
                    const mFin = moment(row.fecha_fin);
                    const duration = moment.duration(mFin.diff(mInicio));
                    const dias = parseInt(duration.asDays());
                    return `${dias}d`;
                }
            },
            { data: "estado", render: estado => `<span class="label label-${estado == 1 ? 'warning' : 'success'}">${estado == 1 ? 'pendiente' : 'aprobado'}</span>` },
            { data: "estado" },
        ],
        'order': [
            [2, 'desc']
        ],
        'columnDefs': [{
                'targets': [0],
                'visible': false
            },
            {
                'targets': [0, 1, 4, 5, 6],
                orderable: false
            },
            {
                'targets': [-1],
                'render': function(data, type, row, meta) {
                    let btns = '<a data-row="' + meta.row + '" data-id="' + row.id + '" data-descripcion="papa" title="eliminar" class="modal-abm-costodet-btn-baja btn" style="padding: 2px;"><i class="glyphicon glyphicon-trash" style="color: red;"></i></a>';
                    return btns;
                }
            }
        ],
        'dom': 'rtpB',

    });

}

/***************************************************************************************
 * Renderizacion Eventos Registro Horas
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {
    const mInicio = moment(info.event.extendedProps.real_start);
    const mFin = moment(info.event.extendedProps.real_end);
    const resource = info.event.getResources()[0];
    const duration = moment.duration(mFin.diff(mInicio)).asDays();

    $(info.el).popover({
        title: `<i class='fa fa-${info.event.extendedProps.icon}'></i> ${info.event.extendedProps.subtipo_desc} <a href="#" class="close" data-dismiss="alert">&times;</a>`,
        placement: 'auto',
        html: true,
        trigger: 'hover',
        content: `
        <div class="row">
            <div class="col-md-4"><strong>Estado:</strong></div>
            <div class="col-md-8 text-right"><span class="label label-${info.event.extendedProps.estado_class}">${info.event.extendedProps.estado_desc}</span></div>
        </div>
        <div class="row">
            <div class="col-md-6"><strong>Comienzo:</strong></div>
            <div class="col-md-6">${mInicio.format('DD/MM/YYYY')}</div>
        </div>
        <div class="row">
            <div class="col-md-6"><strong>Fin:</strong></div>
            <div class="col-md-6">${mFin.format('DD/MM/YYYY')}</div>
        </div>
        <div class="row"><div class="col-md-12 text-right"><i class="fa fa-clock-o"></i> Duracion: ${duration} días</div></div>`,
        container: 'body'
    }).popover('show');

    $(document).on("click", ".popover .close", () => {
        $(".popover").popover('hide');
    });
    $(info.el).off('click').on('click', () => {
        $(this).popover('hide');

    })

    // agrego estylo
    $(info.el).addClass([`ar-tipo-${info.event.extendedProps.tipo}`, `subtipo-${info.event.extendedProps.subtipo}`, `estado-${info.event.extendedProps.estado}`].join(' '))
        // Agrego el ícono
    $(info.el, "div.fc-content").prepend(`<i class='fa fa-${info.event.extendedProps.icon}'></i><span style="padding-left:10px;">Vacaciones</span>`);

    $(info.el).css('cursor', 'pointer');
};

/***************************************************************************************
 * Valida el registro de licencia
 * @param Moment m_inicio - Fecha de inicio del registro de trabajo
 * @param Moment m_fin - Fecha de fin del registro de trabajo
 * @param FullCalendatEvent[] eventosActuales - Eventos de la persona en el período
 * @author MVGP
 ****************************************************************************************/
const validar = (m_inicio, m_fin, eventosActuales) => {
    const resultado = {
        ok: true,
        errores: [],
        warnings: []
    }

    // Validacion Campo del form
    // Valido fechas 
    if (!m_inicio.isValid() || !m_fin.isValid()) {
        resultado.ok = false;
        resultado.errores.push(`Las fechas ingresadas no son válidas.`);
        return resultado;
    }

    // Fecha fin > hoy
    if (m_inicio.isBefore(moment())) {
        resultado.ok = false;
        resultado.errores.push(`El período de licencia no puede ser anterior a la fecha actual.`);
        return resultado;
    }

    // Si ña fecha inicio es < a fin
    if (m_inicio.isAfter(m_fin)) {
        resultado.ok = false;
        resultado.errores.push(`la fecha de inicio no puede ser menor a la fecha fin.`);
        return resultado;
    }

    // Valido de que no se solapen con otras licencias
    if (utils.solapaConLicencia(m_inicio, m_fin, eventosActuales)) {
        resultado.ok = false;
        resultado.errores.push(`Existen licencias registradas en dicho rango de fechas.`);
        return resultado;
    }

    // Valido de que no se solapen con guardias
    if (utils.solapaConGuardia(m_inicio, m_fin, eventosActuales)) {
        resultado.ok = false;
        resultado.warnings.push(`Existe un período de guardia definido para dicho rango de fechas. Deberá informar a su superior para que lo elimine.`);
        return resultado;
    }

    return resultado;
}

/***************************************************************************************
 * guardar Registro en DB
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const submit = (operacion, callback) => {
    let id = $('#modal-abm-cal-lic-id').val();
    let id_persona = $('#modal-abm-cal-lic-id-persona').val();
    let observaciones = $('#modal-abm-cal-lic-observaciones').val();
    let fecha_inicio = $('#modal-abm-cal-lic-inicio').val();
    let fecha_fin = $('#modal-abm-cal-lic-fin').val();

    fecha_inicio = moment(fecha_inicio);
    fecha_fin = moment(fecha_fin);

    // obtengo los eventos para analizar.
    let eventosActuales = calendar.getEvents();

    // Valido nuevo ingreso
    const validez = validar(fecha_inicio, fecha_fin, eventosActuales);
    if (!validez.ok) {
        const { tipo, elementos, title } = (validez.errores.length > 0 ? { tipo: 'error', elementos: validez.errores, title: 'Error en la validación' } : { tipo: 'warning', elementos: validez.warnings, title: 'Advertencia' });
        Swal.fire({
            title,
            html: `<div style="text-align: left;"><li>${elementos.join('</li><li>')}</li></div>`,
            icon: tipo,
            confirmButtonText: 'Aceptar',
        });
        return;
    }

    const subtipo = utils.RULE_CONSTANTS.SUBTIPOS_LICENCIA.VACACIONES; // FIXME: Obtener el sutipo desde el modal 
    // Ejecuto
    $.ajax({
        type: 'POST',
        url: './calendar/licencias/licencias.controller.php',
        dataType: 'json',
        data: {
            operacion,
            fecha_inicio: fecha_inicio.format('YYYY-MM-DD 00:00:00'),
            fecha_fin: fecha_fin.format('YYYY-MM-DD 00:00:00'),
            id_persona,
            descripcion: '',
            observaciones,
            tipo: utils.RULE_CONSTANTS.TIPO_REGISTRO_LICENCIAS,
            subtipo,
            estado: 1,
            is_all_day: 1,
        },
        success: json => {
            $("#modal-abm-cal-lic").modal("hide");
            if (!json.ok) {
                alert(json.err);
            }
            callback(null);
        },
        error: (xhr, status, error) => {
            alert(error);
            callback(error);
        }
    });

}

/***************************************************************************************
 * Eliminar Registro
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const remove = (callback) => {
    if (confirm('¿Está seguro que desea eliminar el registro de la licencia?')) {
        return submitRegistroHoras('REMOVE_LICENCIA', callback);
    }
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const reset = () => {
    let today = moment();
    $('#modal-abm-cal-lic-id').val(0);
    $('#modal-abm-cal-lic-inicio').val(today.local().format('YYYY-MM-DD'));
    $('#modal-abm-cal-lic-fin').val(today.add(1, 'weeks').local().format('YYYY-MM-DD'));
    $('#modal-abm-cal-lic-fin').attr('min', today.local().format('YYYY-MM-DD'));
    $('#modal-abm-cal-lic-observaciones').val('');
    actualizarDuracion();
}

const actualizarDuracion = () => {
    let fecha_inicio = $('#modal-abm-cal-lic-inicio').val();
    let fecha_fin = $('#modal-abm-cal-lic-fin').val();
    const mInicio = moment(fecha_inicio);
    const mFin = moment(fecha_fin);
    if (mInicio.isValid() && mFin.isValid()) {
        const duration = moment.duration(mFin.diff(mInicio)).asDays();
        $('#modal-abm-cal-lic-duracion').html(`${duration} días`);
    } else {
        $('#modal-abm-cal-lic-duracion').html(`Fechas inválidas`);
    }
}

/***************************************************************************************
 * agregar Registro de Hora 
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const agregarVacaciones = () => {
    $('#modal-abm-cal-lic-title').html(`Agregar Licencia`);
    reset();
    $('#modal-abm-cal-lic-submit').attr('name', 'ADD_LICENCIA');
    $("#modal-abm-cal-lic").modal("show");
}



const init = (cal) => {
    calendar = cal;
    $('#modal-abm-cal-lic-submit').on('click', () => submit('ADD_LICENCIA', () => calendar.refetchEvents()));
    $('#modal-abm-cal-lic-remove').on('click', () => remove(() => calendar.refetchEvents()));
    $('#modal-abm-cal-lic-btn-add-vacaciones').on('click', agregarVacaciones);
    $('#modal-abm-cal-lic-inicio,#modal-abm-cal-lic-fin').on('change', actualizarDuracion)
}

export { init, eventRender, eventsUpdated }