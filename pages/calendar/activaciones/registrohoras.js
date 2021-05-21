import * as utils from '../utils.js'

// Calendar instantiation
var calendar;

// ========================================================================================================================================================
// MANEJO DE Registro de HORAS
// ========================================================================================================================================================
// TODO: hacer popup de ayuda 
// TODO: hacer edicion
// TODO: Aprobacion
// TODO: Hacer anánisis y que devuleva un objeto con todo lo que necesitás

/***************************************************************************************
 * Subscripcion a lo actualizacion de eventos
 * @param {Evento[]} eventos - todos los eventos del calendario
 * @author MVGP
 ****************************************************************************************/
const eventsUpdated = eventos => {
    const eventosRegistrosHs = eventos.filter(e => e.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS);
    createTableRegistroHs('tbRegistroHs', eventosRegistrosHs);
}

const createTableRegistroHs = (id, eventos) => {

    let tbRegistro = $(`#${id}`);
    tbRegistro.DataTable().clear().destroy();
    tbRegistro.DataTable({
        "paging": false,
        "deferRender": true,
        "data": eventos,
        "columns": [
            { data: "id" },
            { data: "icon", render: (data, type, row) => `<i title="${row.subtipo_desc}" class="fa fa-${data}"></i>` },
            { data: "fecha_inicio", render: data => moment(data).format('DD/MM/YYYY HH:mm') },
            { data: "fecha_fin", render: data => moment(data).format('DD/MM/YYYY HH:mm') },
            {
                data: "",
                render: (data, type, row) => {
                    const mInicio = moment(row.fecha_inicio);
                    const mFin = moment(row.fecha_fin);
                    const duration = moment.duration(mFin.diff(mInicio));
                    const dhours = parseInt(duration.asHours());
                    const dmin = parseInt(duration.asMinutes()) - (dhours * 60);
                    return `${dhours}h ${dmin}m`;
                }
            },
            { data: "estado", render: (data, type, row) => `<span class="label label-${row.estado_class}">${row.estado_desc}</span>` },
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
 * Aprobar un evento de hs
 * @param {FullcalendarEvent} evento - Informacion del evento
 * @author MVGP
 ****************************************************************************************/
const cambiarEstado = (evento, observaciones, estado) => {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: 'POST',
            url: './calendar/activaciones/registrohoras.controller.php',
            dataType: 'json',
            data: {
                id: evento.id,
                estado,
                observaciones,
                operacion: 'CAMBIAR_ESTADO'
            },
            success: json => {
                if (!json.ok) {
                    reject(error);
                }
                resolve(json);
            },
            error: (xhr, status, error) => {
                reject(error);
            }
        });
    })


}

/***************************************************************************************
 * Renderizacion del Formulario de aprobación
 * @param {EventInformation} info - Informacion del evento (DOM element, event)
 * @returns {String} html in string template
 * @author MVGP
 ****************************************************************************************/
const popoverEventObs = info => {

    return `<div class="col-md-12">
                <div class="row">
                    <hr style="margin: 5px;">
                    <div class="form-group">
                        <label for="observaciones">Observaciones/Motivo</label><br>
                        ${info.event.extendedProps.observaciones}
                    </div>   
                </div>
                <br>
            </div> `;
}

/***************************************************************************************
 * Renderizacion del Formulario de aprobación
 * @param {EventInformation} info - Informacion del evento (DOM element, event)
 * @returns {String} html in string template
 * @author MVGP
 ****************************************************************************************/
const popoverEventApprovalForm = info => {

    return `<div class="col-md-12">
                <div class="row">
                    <hr style="margin: 5px;">
                    <div class="form-group">
                        <label for="observaciones">Observaciones/Motivo</label>
                        <textarea class=form-control name="observaciones"></textarea>
                    </div>   
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-xs btn-success aprobar">Aprobar</button>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-xs btn-danger rechazar" >Rechazar</button>
                    </div>
                </div>
                <br>
            </div> `;
}

/***************************************************************************************
 * Renderizacion de la parte del detalle comun a todos los eventos de este tipo
 * @param {EventInformation} info - Informacion del evento (DOM element, event)
 * @returns {String} html in string template
 * @author MVGP
 ****************************************************************************************/
const popoverEventDetailContent = info => {
    const mInicio = moment(info.event.extendedProps.real_start);
    const mFin = moment(info.event.extendedProps.real_end);
    const resource = info.event.getResources()[0];
    const duration = moment.duration(mFin.diff(mInicio));
    const dhours = parseInt(duration.asHours());
    const dmin = parseInt(duration.asMinutes()) - (dhours * 60);
    return `
        <div class="row">
            <div class="col-md-4"><strong>Estado:</strong></div>
            <div class="col-md-8 text-right"><span class="label label-${info.event.extendedProps.estado_class}">${info.event.extendedProps.estado_desc}</span></div>
        </div>
        <div class="row">
            <div class="col-md-4"><strong>Comienzo:</strong></div>
            <div class="col-md-8 text-right">${mInicio.format('DD/MM/YYYY HH:mm')}</div>
        </div>
        <div class="row">
            <div class="col-md-4"><strong>Fin:</strong></div>
            <div class="col-md-8 text-right">${mFin.format('DD/MM/YYYY HH:mm')}</div>
        </div>
        <div class="row"><div class="col-md-12 text-right"><i class="fa fa-clock-o"></i> Duracion: ${dhours} h ${dmin} m</div></div>    
        <div class="col-md-12">
            <div class="row">
                <strong>Justificación:</strong><br>${info.event.extendedProps.justificacion}
            </div>
        </div> `;


}

/***************************************************************************************
 * Renderizacion Eventos Registro Horas con formulario de aprobacion
 * @param {EventInformation} info - Informacion del evento (DOM element, event)
 * @author MVGP
 ****************************************************************************************/
const eventRenderConAprobacion = info => {


    $(info.el).popover({
            title: `<i class='fa fa-${info.event.extendedProps.icon}'></i> ${info.event.extendedProps.subtipo_desc} <a href="#" class="close" data-dismiss="alert">&times;</a>`,
            placement: 'auto',
            html: true,
            trigger: 'hover',
            content: `${popoverEventDetailContent(info)}
                      ${(info.event.extendedProps.estado == utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.PENDIENTE ? popoverEventApprovalForm(info) : '')}
                      ${(info.event.extendedProps.observaciones ? popoverEventObs(info) : '' )}
                `,
            container: 'body',
            trigger: "manual",
            animation: false
        })
        .on("mouseenter", function() {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function() {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function() {
            var _this = this;
            setTimeout(function() {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                }
            }, 0);
        })
        .on('shown.bs.popover', (eventShown) => {
            let $popup = $('#' + $(eventShown.target).attr('aria-describedby'));
            let $observaciones = $popup.find('textarea');
            $popup.find('button.rechazar').on('click', (e) => {
                cambiarEstado(info.event, $observaciones.val(), utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.RECHAZADO)
                    .then(res => {
                        $popup.popover('hide');
                        calendar.refetchEvents();
                    })
                    .catch(err => alert(err))
            });
            $popup.find('button.aprobar').on('click', (e) => {
                cambiarEstado(info.event, $observaciones.val(), utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.APROBADO)
                    .then(res => {
                        $popup.popover('hide');
                        calendar.refetchEvents();
                    })
                    .catch(err => alert(err))
            });
        });

    $(document).off('click').on("click", ".popover .close", () => {
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
 * Renderizacion Eventos Registro Horas
 * @param {EventInformation} info - Informacion del evento (DOM element, event)
 * @author MVGP
 ****************************************************************************************/
const eventRender = info => {

    $(info.el).popover({
            title: `<i class='fa fa-${info.event.extendedProps.icon}'></i> ${info.event.extendedProps.subtipo_desc} <a href="#" class="close" data-dismiss="alert">&times;</a>`,
            placement: 'auto',
            html: true,
            trigger: 'hover',
            content: `${popoverEventDetailContent(info)}
                      ${(info.event.extendedProps.observaciones ? popoverEventObs(info) : '' )}
                `,
            container: 'body',
            trigger: "manual",
            animation: false
        })
        .on("mouseenter", function() {
            var _this = this;
            $(this).popover("show");
            $(".popover").on("mouseleave", function() {
                $(_this).popover('hide');
            });
        }).on("mouseleave", function() {
            var _this = this;
            setTimeout(function() {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide");
                }
            }, 0);
        });

    $(document).off('click').on("click", ".popover .close", () => {
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
const validar = (m_inicio, m_fin, es_programada, justificacion, eventosActuales) => {
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
    const { excede, totalMinutos } = utils.verificarLimiteAcumuladoMensual(
        m_inicio,
        m_fin,
        eventosActuales,
        utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS,
        utils.RULE_CONSTANTS.RULE_CANTIDAD_MAX_HS_ACTIVACION_MENSUAL);
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
const submit = (operacion, callback) => {
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
    const validez = validar(fecha_inicio, fecha_fin, es_programada, justificacion, eventosActuales);
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
const remove = (callback) => {
    if (confirm('¿Está seguro que desea eliminar el registro de horas?')) {
        return submit('REMOVE_REGISTRO_HORAS', callback);
    }
}

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const reset = () => {
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
    reset();
    $('#modal-abm-cal-registro-submit').attr('name', 'ADD_REGISTRO_HORAS');
    $("#modal-abm-cal-registro").modal("show");
}


const init = (cal) => {
    calendar = cal;
    $('#modal-abm-cal-registro-submit').on('click', () => submit('ADD_REGISTRO_HS', () => calendar.refetchEvents()));
    $('#modal-abm-cal-registro-remove').on('click', () => remove(() => calendar.refetchEvents()));
    $('#modal-abm-registro-btn-add').on('click', agregarRegistroHoras);
    $('#modal-abm-cal-registro-inicio,#modal-abm-cal-registro-fin').on('change', actualizarDuracion)
}

export { init, eventRender, eventRenderConAprobacion, eventsUpdated }