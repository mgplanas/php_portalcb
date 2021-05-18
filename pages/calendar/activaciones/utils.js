/***************************************************************************************
 * Constantes
 ****************************************************************************************/
const RULE_RANGO_LABORAL_INICIO = '09:00:00';
const RULE_RANGO_LABORAL_FIN = '17:30:00';
const RULE_CANTIDAD_MAX_HS_ACTIVACION_MENSUAL = 30;
const RULE_CANTIDAD_MAX_HS_ACTIVACION_ANUAL = 200;
const TIPO_REGISTRO_FERIADOS = '1';
const TIPO_REGISTRO_GUARDIAS = '2';
const TIPO_REGISTRO_LICENCIAS = '3';
const TIPO_REGISTRO_HORAS = '4';


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
const verificarLimiteAcumuladoMensual = (inicio, fin, eventos) => {
    const res = {
        excede: false,
        cantidadMinActuales: cantidadMinAcumulados(eventos),
        minEventoActual: moment.duration(fin.diff(inicio)).asMinutes(),
        totalMinutos: 0
    }
    res.totalMinutos = res.cantidadMinActuales + res.minEventoActual;
    res.excede = (res.totalMinutos) > (30 * 60);

    return res;
}

/***************************************************************************************
 * Verifica limite mensual de horas
 * @param Event[] eventos - Eventos de la persona 
 * @author MVGP
 ****************************************************************************************/
const cantidadMinAcumulados = (eventos) => {
    return eventos
        .filter(event => event.extendedProps.tipo == TIPO_REGISTRO_HORAS)
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
    const comienzo_jornada = moment(`${fecha.format('YYYY-MM-DD')} ${RULE_RANGO_LABORAL_INICIO}`);
    const fin_jornada = moment(`${fecha.format('YYYY-MM-DD')} ${RULE_RANGO_LABORAL_FIN}`);
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
    return eventos.filter(ev => ev.extendedProps.tipo == 4).length > 0;
}




export {
    esDNL,
    esWeekEnd,
    estaEnEsquemaDeGuardia,
    estanSolapados,
    solapaRangoConHorarioLaboral,
    solapaHorarioLaboral,
    verificarLimiteAcumuladoMensual
}