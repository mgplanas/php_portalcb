/***************************************************************************************
 * Constantes
 ****************************************************************************************/
const RULE_CONSTANTS = {
    RULE_RANGO_LABORAL_INICIO: '09:00:00',
    RULE_RANGO_LABORAL_FIN: '17:30:00',
    RULE_CANTIDAD_MAX_DIAS_GUARDIAS: 17,
    RULE_CANTIDAD_MAX_HS_ACTIVACION_MENSUAL: 30,
    RULE_CANTIDAD_MAX_HS_ACTIVACION_ANUAL: 200,
    TIPO_REGISTRO_FERIADOS: 1,
    TIPO_REGISTRO_GUARDIAS: 2,
    TIPO_REGISTRO_LICENCIAS: 3,
    TIPO_REGISTRO_HORAS: 4,
    SUBTIPOS_REGISTRO_HORAS: {
        ACTIVACION: 1,
        EMERGENCIA: 2,
        TAREA_PROGRAMADA: 3,
        HORAS_EXTRAS: 4,
    },
    SUBTIPOS_LICENCIA: {
        VACACIONES: 1,
        ENFERMEDAD: 2,
    },
    ESTADOS_REGISTRO_HORAS: {
        PENDIENTE: 1,
        APROBADO: 2,
        RECHAZADO: 3,
    }
}


/***************************************************************************************
 * Verifica limite mensual de horas
 * @typedef {Object} Resultado
 * @property {boolean} excede - si excede el limite
 * @property {number} cantidadMinActuales - cantidad de minutos de los eventos actuales
 * @property {number} minEventoActual - cantidad de minutos del evento a validar
 * @param Moment inicio - incio del evento a validar
 * @param Moment fin - fin del evento a Validar
 * @param Event[] eventos - Eventos de la persona 
 * @returns {Resultado}
 * @author MVGP
 ****************************************************************************************/
const verificarLimiteAcumuladoMensual = (inicio, fin, eventos, tipoEvento, limite) => {
    const eventosNORechazados = eventos.filter(event => event.extendedProps.estado != RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.RECHAZADO);
    const res = {
        excede: false,
        cantidadMinActuales: cantidadMinAcumulados(eventosNORechazados, tipoEvento),
        minEventoActual: moment.duration(fin.diff(inicio)).asMinutes(),
        totalMinutos: 0
    }
    res.totalMinutos = res.cantidadMinActuales + res.minEventoActual;
    res.excede = (res.totalMinutos) > (limite * 60);

    return res;
}

/***************************************************************************************
 * Minutoa acumulados en un periodo por persona y tipo
 * @param {number} id_persona - ID de persona 
 * @param {Moment} inicio - Inicio del periodo 
 * @param {Moment} fin - fin del periodo 
 * @param {number} tipo - tipo de evento
 * @author MVGP
 ****************************************************************************************/
const cantidadMinAcumuladosPeriodoByPerson = (id_persona, start, end, tipo) => {

    return new Promise((resolve, reject) => {
        $.ajax({
            type: 'POST',
            url: './calendar/stats/stats.controller.php',
            data: {
                action: 'MIN_ACUM_BY_PERSON',
                id_persona,
                start,
                end,
                tipo
            },
            dataType: 'json',
            success: (json) => resolve(json.data[0]),
            error: (xhr, status, error) => reject(error)
        });

    });
}

/***************************************************************************************
 * Verifica limite mensual de horas
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const cantidadMinAcumulados = (eventos, tipoEvento) => {
    return eventos
        .filter(event => event.extendedProps.tipo == tipoEvento)
        .reduce((acumulado, evento) => {
            const mInicio = moment(evento.start);
            const mFin = moment(evento.end);
            const duration = moment.duration(mFin.diff(mInicio));
            return acumulado + parseInt(duration.asMinutes());
        }, 0);
}

/***************************************************************************************
 * Verifica un evento está solapdado con otro
 * @param Moment a_inicio - incio del primer evento
 * @param Moment a_fin - fin del primer evento
 * @param Moment b_inicio - incio del segundo evento
 * @param Moment b_fin - fin del segundo evento
 * @author MVGP
 ****************************************************************************************/
const estanSolapados = (a_inicio, a_fin, b_inicio, b_fin) => {
    return (
        a_inicio.isBetween(b_inicio, b_fin, 'minutes', "()") ||
        a_fin.isBetween(b_inicio, b_fin, 'minutes', "()")
    );
}

/***************************************************************************************
 * Verifica si una fecha es Feriado o Día no laborable
 * @param Moment fecha - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const solapaHorarioLaboral = (fecha, eventos) => {

    if (esWeekEnd(fecha)) return false;

    if (esDNL(fecha, eventos)) return false;

    // formo el rango inicio fin para la fecha a verificar
    const comienzo_jornada = moment(`${fecha.format('YYYY-MM-DD')} ${RULE_CONSTANTS.RULE_RANGO_LABORAL_INICIO}`);
    const fin_jornada = moment(`${fecha.format('YYYY-MM-DD')} ${RULE_CONSTANTS.RULE_RANGO_LABORAL_FIN}`);
    return (fecha.isBetween(comienzo_jornada, fin_jornada, 'minutes', "()"));
}

/***************************************************************************************
 * Verifica si un rango se spolapa con horario laboral
 * @param Moment inicio - Fecha a verificar en Moment
 * @param Moment fin - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const solapaRangoConHorarioLaboral = (inicio, fin, eventos) => {
    return (solapaHorarioLaboral(inicio, eventos) || solapaHorarioLaboral(fin, eventos));
}



/***************************************************************************************
 * Verifica si una fecha es Feriado o Día no laborable
 * @param Date inicio - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const esDNL = (m_fecha, eventos) => {
    const feriadosQueAplican = eventos
        .filter(e => e.extendedProps.tipo == 1) // Feriados DNL
        .filter(el => {
            const m_inicio = moment(el.start);
            const res = (m_fecha.format('YYYY-MM-DD') == m_inicio.format('YYYY-MM-DD'));
            return res;
        });

    return feriadosQueAplican.length > 0;

}

/***************************************************************************************
 * Verifica si una fecha se solapa con un evento de licencia
 * @param Moment inicio - Fecha a verificar en Moment
 * @param Moment fin - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const solapaConLicencia = (m_inicio, m_fin, eventos, subtipo) => {
    const licenciasQueAplican = eventos
        .filter(e => e.extendedProps.tipo == RULE_CONSTANTS.TIPO_REGISTRO_LICENCIAS &&
            (!subtipo || e.extendedProps.subtipo == subtipo)
        )
        .filter(e => {
            const e_inicio = moment(e.start);
            const e_fin = moment(e.end);
            return estanSolapados(m_inicio, m_fin, e_inicio, e_fin);
        });

    return licenciasQueAplican.length > 0;

}

/***************************************************************************************
 * Verifica si una fecha se solapa con un período de guardia
 * @param Moment inicio - Fecha a verificar en Moment
 * @param Moment fin - Fecha a verificar en Moment
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const solapaConGuardia = (m_inicio, m_fin, eventos) => {
    const guardiasQueAplican = eventos
        .filter(e => e.extendedProps.tipo == RULE_CONSTANTS.TIPO_REGISTRO_GUARDIAS) // Feriados DNL
        .filter(e => {
            const e_inicio = moment(e.extendedProps.real_start);
            const e_fin = moment(e.extendedProps.real_end);
            return estanSolapados(m_inicio, m_fin, e_inicio, e_fin);
        });

    return guardiasQueAplican.length > 0;

}

/***************************************************************************************
 * Verifica si una fecha es Fin de Semana
 * @param Date inicio - Fecha a verificar en Moment
 * @author MVGP
 ****************************************************************************************/
const esWeekEnd = (m_fecha) => {
    return (m_fecha.isoWeekday() >= 6);
}

/***************************************************************************************
 * Verifica si posee esquema de guardias
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const estaEnEsquemaDeGuardia = (eventos) => {
    return eventos.filter(ev => ev.extendedProps.tipo == RULE_CONSTANTS.TIPO_REGISTRO_GUARDIAS).length > 0;
}

/***************************************************************************************
 * Determina el subtipo de un registro de horas
 * @param Moment inicio - Fecha a verificar en Moment
 * @param Moment fin - Fecha a verificar en Moment
 * @param {boolean} es_programada - flag que determina si la tarea es programada
 * @param FullCalendarEvent[] eventos - Eventos de la persona 
 * @returns {number} subtipo
 * @author MVGP
 ****************************************************************************************/
const determinarSubtipoRegistroHoras = (m_inicio, m_fin, es_programada, eventos) => {

    // verfifico si pertenece a un esquema de guardias
    if (estaEnEsquemaDeGuardia(eventos)) {
        // si cae dentro de una gaurdia
        if (solapaConGuardia(m_inicio, m_fin, eventos)) {
            if (es_programada) return RULE_CONSTANTS.SUBTIPOS_REGISTRO_HORAS.TAREA_PROGRAMADA;
            else return RULE_CONSTANTS.SUBTIPOS_REGISTRO_HORAS.ACTIVACION;
        } else {
            // determino entre hs extra y tarea Programada
            if (es_programada) return RULE_CONSTANTS.SUBTIPOS_REGISTRO_HORAS.TAREA_PROGRAMADA;
            else return RULE_CONSTANTS.SUBTIPOS_REGISTRO_HORAS.EMERGENCIA;
        }
    } else {
        // determino entre hs extra y tarea Programada
        if (es_programada) return RULE_CONSTANTS.SUBTIPOS_REGISTRO_HORAS.TAREA_PROGRAMADA;
        else return RULE_CONSTANTS.SUBTIPOS_REGISTRO_HORAS.HORAS_EXTRAS;
    }

    return 0;
}


export {
    esDNL,
    esWeekEnd,
    estaEnEsquemaDeGuardia,
    estanSolapados,
    solapaRangoConHorarioLaboral,
    solapaHorarioLaboral,
    verificarLimiteAcumuladoMensual,
    solapaConLicencia,
    solapaConGuardia,
    determinarSubtipoRegistroHoras,
    cantidadMinAcumulados,
    RULE_CONSTANTS,
    cantidadMinAcumuladosPeriodoByPerson
}