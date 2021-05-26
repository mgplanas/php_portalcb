import * as utils from '../../utils.js'

/***************************************************************************************
 * limpiar los campos del modal
 * @author MVGP
 ****************************************************************************************/
const reset = () => {
    $('#modal-cal-per-stat-id').val(0);
    $('#modal-cal-per-stat-hs-per').html(0);
    $('#modal-cal-per-stat-hs-container').removeClass('bg-green bg-yellow bg-red');
}

/***************************************************************************************
 * agregar Registro de Hora 
 * @author MVGP
 ****************************************************************************************/
const showStatsForPerson = (resource, cal, events) => {
    let eventos = resource.getEvents();
    $('#modal-cal-per-stat-title').html(`Estadísticas para ${resource.title}`);
    reset();
    const eventosHsNORechazados = eventos
        .filter(event => event.extendedProps.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS)
        .filter(event => event.extendedProps.estado != utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.RECHAZADO);
    const hs = utils.cantidadMinAcumulados(eventosHsNORechazados, utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS) / 60;
    $('#modal-cal-per-stat-hs-anual').html('N/D');
    utils.cantidadMinAcumuladosPeriodoByPerson(101, '2021-01-01', '2021-12-31', 4)
        .then(res => $('#modal-cal-per-stat-hs-anual').html(res.suma / 60))
        .catch(err => console.log(err));
    $('#modal-cal-per-stat-hs-per').html(hs);
    $("#modal-cal-per-stat").modal("show");
}


export { showStatsForPerson }