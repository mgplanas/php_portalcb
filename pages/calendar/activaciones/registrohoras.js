import * as utils from '../utils.js'

// Calendar instantiation
var calendarEl = document.getElementById('calendar');
var calendar;

// Default dates range
var today = new Date();

// ========================================================================================================================================================
// MANEJO DE Registro de HORAS
// ========================================================================================================================================================
// TODO: hacer popup de ayuda 
// TODO: hacer edicion
// TODO: validación sobre eventos actuales
// TODO: generacion de eventos que pasan 
// TODO: Aprobacion
// TODO: Hacer anánisis y que devuleva un objeto con todo lo que necesitás


/***************************************************************************************
 * Renderizacion Eventos Registro Horas
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {
    const mInicio = moment(info.event.extendedProps.real_start);
    const mFin = moment(info.event.extendedProps.real_end);
    const resource = info.event.getResources()[0];
    const duration = moment.duration(mFin.diff(mInicio));
    const dhours = parseInt(duration.asHours());
    const dmin = parseInt(duration.asMinutes()) - (dhours * 60);

    $(info.el).popover({
        title: `<i class='fa fa-${info.event.extendedProps.icon}'></i> ${info.event.extendedProps.subtipo_desc} <a href="#" class="close" data-dismiss="alert">&times;</a>`,
        placement: 'top',
        html: true,
        trigger: 'hover',
        content: `<strong>${resource.title}:</strong><br>
        <strong>Comienzo:</strong>${mInicio.format('DD/MM/YYYY HH:mm')}<br>
        <strong>Fin:</strong>${mFin.format('DD/MM/YYYY HH:mm')}<br>
        <i class="fa fa-clock-o"></i> Duracion: ${dhours} h ${dmin} m
        <hr>
        <div class="text-right"><strong>Estado:</strong> <span class="label label-warning">pendiente aprobación</span></div>`,
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
    $(info.el, "div.fc-content").prepend(`<i class='fa fa-${info.event.extendedProps.icon}'></i>`);
    $(info.el).css('cursor', 'pointer');
};

/***************************************************************************************
 * Valida el registro de horas
 * @param Moment m_inicio - Fecha de inicio del registro de trabajo
 * @param Moment m_fin - Fecha de fin del registro de trabajo
 * @param FullCalendatEvent[] eventosActuales - Eventos de la persona en el período
 * @author MVGP
 ****************************************************************************************/
const validarRegistroDeHora = (m_inicio, m_fin, es_programada, justificacion, eventosActuales) => {
    const resultado = {
        ok: true,
        errores: []
    }

    // Validacion Campo del form
    // Valido fechas 
    if (!m_inicio.isValid() || !m_fin.isValid()) {
        resultado.ok = false;
        resultado.errores.push(`Las fechas ingresadas no son válidas.`);
        return resultado;
    }

    // Fecha fin > hoy
    if (m_fin.isAfter(moment())) {
        resultado.ok = false;
        resultado.errores.push(`la fecha de fin no puede ser a futuro.`);
        return resultado;
    }

    // Si ña fecha inicio es < a fin
    if (m_inicio.isAfter(m_fin)) {
        resultado.ok = false;
        resultado.errores.push(`la fecha de inicio no puede ser menor a la fecha fin.`);
        return resultado;
    }
    // Valido justificacion 
    if (justificacion === '') {
        resultado.ok = false;
        resultado.errores.push(`El campo Justificación no puede estar vacío.`);
        return resultado;
    }

    // límite de horas de trabajos seguido
    if (m_fin.diff(m_inicio, 'days') >= 1) {
        resultado.ok = false;
        resultado.errores.push(`El límite de horas trabajadas supera el día.`);
        return resultado;
    }

    // Valido de que no se solapen con horarios laborales
    if (utils.solapaConLicencia(m_inicio, m_fin, eventosActuales)) {
        resultado.ok = false;
        resultado.errores.push(`No es posible ingresar horas en un período de licencia.`);
        return resultado;
    }

    // Valido de que no se solapen con horarios laborales
    if (utils.solapaRangoConHorarioLaboral(m_inicio, m_fin, eventosActuales)) {
        resultado.ok = false;
        resultado.errores.push(`El rango ingresado se solapa con horario laboral.`);
        return resultado;
    }

    // Límite de hs acumuladas
    const { excede, totalMinutos } = utils.verificarLimiteAcumuladoMensual(m_inicio, m_fin, eventosActuales);
    if (excede) {
        resultado.ok = false;
        resultado.errores.push(`Se está excediendo el límite de horas mensuales (30hs).`);
        return resultado;
    }

    return resultado;
}

/***************************************************************************************
 * guardar Registro en DB
 * @param callback() callback fuanction
 * @author MVGP
 ****************************************************************************************/
const submitRegistroHoras = (operacion, callback) => {
    let id = $('#modal-abm-cal-registro-id').val();
    let id_persona = $('#modal-abm-cal-registro-id-persona').val();
    let justificacion = $('#modal-abm-cal-registro-justificacion').val();
    let fecha_inicio = $('#modal-abm-cal-registro-inicio').val();
    let fecha_fin = $('#modal-abm-cal-registro-fin').val();
    let es_programada = 0;
    if ($("#modal-abm-cal-registro-programada").is(':checked')) {
        es_programada = 1;
    }

    fecha_inicio = moment(fecha_inicio);
    fecha_fin = moment(fecha_fin);

    // obtengo los eventos para analizar.
    let eventosActuales = calendar.getEvents();

    // Valido nuevo ingreso
    const validez = validarRegistroDeHora(fecha_inicio, fecha_fin, es_programada, justificacion, eventosActuales);
    if (!validez.ok) {
        Swal.fire({
            title: 'Error en la validación',
            html: `<div style="text-align: left;"><li>${validez.errores.join('</li><li>')}</li></div>`,
            icon: 'error',
            confirmButtonText: 'Aceptar',
        });
        return;
    }

    const subtipo = utils.determinarSubtipoRegistroHoras(fecha_inicio, fecha_fin, es_programada, eventosActuales);
    // Ejecuto
    $.ajax({
        type: 'POST',
        url: './calendar/activaciones/registrohoras.controller.php',
        dataType: 'json',
        data: {
            operacion,
            fecha_inicio: fecha_inicio.format('YYYY-MM-DD HH:mm:ss'),
            fecha_fin: fecha_fin.format('YYYY-MM-DD HH:mm:ss'),
            id_persona,
            justificacion,
            tipo: utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS,
            subtipo,
            estado: 1,
            is_all_day: 0,
            is_background: 0,
            is_programmed: es_programada
        },
        success: json => {
            $("#modal-abm-cal-registro").modal("hide");
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
const removeRegistroHoras = (callback) => {
    if (confirm('¿Está seguro que desea eliminar el registro de horas?')) {
        return submitRegistroHoras('REMOVE_REGISTRO_HORAS', callback);
    }
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const modalRegistroHorasLimpiarCampos = () => {
    // let today = new Date();
    // today.setSeconds(0, 0);
    let today = moment();
    $('#modal-abm-cal-registro-id').val(0);
    $('#modal-abm-cal-registro-inicio').val(today.add(-1, 'hours').local().format('YYYY-MM-DDTHH:mm'));
    $('#modal-abm-cal-registro-fin').val(today.add(1, 'hours').local().format('YYYY-MM-DDTHH:mm'));
    $('#modal-abm-cal-registro-fin').attr('max', today.local().format('YYYY-MM-DDTHH:mm'));
    $('#modal-abm-cal-registro-justificacion').val('');
    actualizarDuracion();
}

const actualizarDuracion = () => {
    let fecha_inicio = $('#modal-abm-cal-registro-inicio').val();
    let fecha_fin = $('#modal-abm-cal-registro-fin').val();
    const mInicio = moment(fecha_inicio);
    const mFin = moment(fecha_fin);
    if (mInicio.isValid() && mFin.isValid()) {
        const duration = moment.duration(mFin.diff(mInicio));
        const dhours = parseInt(duration.asHours());
        const dmin = parseInt(duration.asMinutes()) - (dhours * 60);
        $('#modal-abm-cal-registro-duracion').html(`${dhours} h ${dmin} m`);
    } else {
        $('#modal-abm-cal-registro-duracion').html(`Fechas inválidas`);
    }
}

/***************************************************************************************
 * agregar Registro de Hora 
 * @param Date inicio - inicio del rango de búsqueda
 * @param Date fin - fin del rango de búsqueda
 * @author MVGP
 ****************************************************************************************/
const agregarRegistroHoras = () => {
    $('#modal-abm-cal-registro-title').html(`Agregar Registro de horas trabajadas`);
    modalRegistroHorasLimpiarCampos();
    $('#modal-abm-cal-registro-submit').attr('name', 'ADD_REGISTRO_HORAS');
    $("#modal-abm-cal-registro").modal("show");
}


const init = (cal) => {
    calendar = cal;
    $('#modal-abm-cal-registro-submit').on('click', () => submitRegistroHoras('ADD_REGISTRO_HS', () => calendar.refetchEvents()));
    $('#modal-abm-cal-registro-remove').on('click', () => removeRegistroHoras(() => calendar.refetchEvents()));
    $('#modal-abm-registro-btn-add').on('click', agregarRegistroHoras);
    $('#modal-abm-cal-registro-inicio,#modal-abm-cal-registro-fin').on('change', actualizarDuracion)
}

export { init, eventRender }