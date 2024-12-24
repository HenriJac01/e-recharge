document.addEventListener("DOMContentLoaded", function () {

    // Initialisation pour "Evolution des Recharges"
    var chartEvo = new ApexCharts(document.querySelector("#chartEvo"), optionsDays);
    chartEvo.render();

    document.getElementById("dayBtnEvo").addEventListener("click", function () {
        chartEvo.updateOptions(optionsDays, true);
    });

    document.getElementById("weekBtnEvo").addEventListener("click", function () {
        chartEvo.updateOptions(optionsWeeks, true);
    });

    document.getElementById("monthBtnEvo").addEventListener("click", function () {
        chartEvo.updateOptions(optionsMonths, true);
    });

    document.getElementById("yearBtnEvo").addEventListener("click", function () {
        chartEvo.updateOptions(optionsYears, true);
    });

    // Activation pour les boutons Evolution
    const btnEvo = document.querySelectorAll(".chart-evo button");
    btnEvo.forEach((item) => {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            btnEvo.forEach((link) => link.classList.remove("active"));
            item.classList.add("active");
        });
    });


});