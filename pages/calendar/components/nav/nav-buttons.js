const init = (cal) => {
    $('#cal-nav-btn-periodo').on('click', () => cal.changeView('byPeriod'));
    $('#cal-nav-btn-mes').on('click', () => cal.changeView('monthview'));
    $('#cal-nav-btn-prev').on('click', () => cal.incrementDate({ months: -1 }));
    $('#cal-nav-btn-next').on('click', () => cal.incrementDate({ months: 1 }));
}

export { init }