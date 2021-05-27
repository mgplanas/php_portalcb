import * as utils from '../../utils.js'

let gx_top_10 = null;

const gxTopTenHsByEventsUpdate = (events, p_inicio, p_fin) => {

    // Eventos de Hs no rechazados
    const eventosRegistrosHs = events.filter(e => e.tipo == utils.RULE_CONSTANTS.TIPO_REGISTRO_HORAS &&
        e.estado != utils.RULE_CONSTANTS.ESTADOS_REGISTRO_HORAS.RECHAZADO);
    gxTopTenHsByEvents(eventosRegistrosHs, moment(p_inicio), moment(p_fin));
}

const gxTopTenHsByEvents = (eventos, p_inicio, p_fin) => {

    if (gx_top_10 != null) {
        gx_top_10.destroy();
    }

    let res = {};
    eventos.forEach(e => {
        let mInicio = moment(e.fecha_inicio);
        let mFin = moment(e.fecha_fin);
        if (p_inicio.isBetween(mInicio, mFin, 'minutes', '()')) mInicio = p_inicio;
        if (p_fin.isBetween(mInicio, mFin, 'minutes', '()')) mFin = p_fin;

        if (res.hasOwnProperty(e.fullname)) {
            res[e.fullname] += moment.duration(mFin.diff(mInicio)).asMinutes() / 60;
        } else {
            res[e.fullname] = moment.duration(mFin.diff(mInicio)).asMinutes() / 60;;
        }
    });
    //.sort((a, b) => a.suma - b.suma);

    let data = [];
    let name = [];
    let suma = [];
    for (let total in res) {
        data.push([total, res[total]]);
    }
    data.sort((a, b) => b[1] - a[1]);
    data.forEach(a => {
        name.push(a[0]);
        suma.push(a[1].toFixed(2));
    })
    console.log(res);
    console.log(data);
    console.log(name);
    console.log(suma);


    let chartdata = {
        labels: name,
        datasets: [{
            label: 'Acumulado [Hs]',
            data: suma,
            backgroundColor: 'rgb(243, 156, 18)'
        }]
    };
    let options = {
        responsive: true,
        legend: {
            display: false,
            position: "top",
            labels: {
                fontColor: "#333",
                fontSize: 16
            }
        },
        scales: {
            xAxes: [{ stacked: true }],
            yAxes: [{ stacked: true }]
        }
    };

    let graphTarget = $("#gx_top_10_hs");

    gx_top_10 = new Chart(graphTarget, {
        type: 'horizontalBar',
        data: chartdata,
        options: options
    });
}

export { gxTopTenHsByEventsUpdate }