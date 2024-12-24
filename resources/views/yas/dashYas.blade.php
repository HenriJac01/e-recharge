@extends('layouts/yas')


@section('content')
<h2 class="title">Tableau de bord</h2>
<ul class="breadcrumbs">
  <li><a href="">Accueil</a></li>
  <li><a href="" class="divider">/</a></li>
  <li><a href="" class="active">Tableau de bord</a></li>
</ul>
<div class="info-data">
  <div class="card">
    <div class="head">
      <div class="head-section">
        <i class="bx bx-receipt"></i>
        <span class="curent-a">{{ number_format($stats['nombre_recharge_rate'], 2) }}%</span>
      </div>
      <p>Nombre total</p>
      <div class="count">
        <h2>{{ number_format($stats['total']) }}</h2>
        <p class="title_mont">Recharge</p>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="head">
      <div class="head-section">
        <i class="bx bx-wallet"></i>
        <span class="curent-b">{{ number_format($stats['total_recharge_rate'], 2) }}%</span>
      </div>
      <p>Montant total</p>
      <div class="count">
        <h2>{{ number_format($stats['total_amount']) }} Ar</h2>
        <p class="title_mont">Recharge</p>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="head">
      <div>
        <i class="bx bx-check-circle"></i>
      </div>
      <p>Taux de transaction</p>
      <div>
        <span class="process" data-value="{{ number_format($stats['success_rate'], 2) }}%"></span>
        <span class="label">{{ number_format($stats['success_rate'], 2) }}%</span>
      </div>
    </div>
  </div>
</div>
<div class="data">
  <div class="content_data">
    <div class="head">
      <h3>Evolution des recharges</h3>
      <div class="chart-evo">
        <button id="dayBtnEvo" class="active">Jour</button>
        <button id="weekBtnEvo">Semaine</button>
        <button id="monthBtnEvo">Mois</button>
        <button id="yearBtnEvo">Année</button>
      </div>
      <!-- Conteneur pour le graphique -->
      <div class="chart">
        <div id="chartEvo"></div>
      </div>
    </div>
  </div>
  <div class="content_data">
    <div class="head">
      <h3>Revenus par période</h3>
      <div class="chart-rev">
        <button id="dayBtnRev" class="active">Jour</button>
        <button id="weekBtnRev">Semaine</button>
        <button id="monthBtnRev">Mois</button>
        <button id="yearBtnRev">Année</button>
      </div>
      <!-- Conteneur pour le graphique -->
      <div class="chart">
        <div id="chartRev"></div>
      </div>
    </div>
  </div>
</div>

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
            <div class="p-6 ">
            <div class="section-header">
                <h3>Transactions récentes</h3>
                </div>
            <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Heure</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expediteur</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destinataire</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                               
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recent_transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($transaction['created_at'])->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction['reference'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $transaction['sender']['name'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction['sender']['phone'] ?? 'N/A' }}</div>
                                  </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $transaction['recipient']['name'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction['recipient']['phone'] ?? 'N/A' }}</div>
                                 </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($transaction['amount']) }} Ar
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($transaction['status'])
                                        @case('completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Complété
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $transaction['status'] }}
                                            </span>
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<script>
    // Préparer les données
    const datesDaily = @json($dailyRevenues->pluck('day'));
    const totalsDaily = @json($dailyRevenues->pluck('total_revenue'));
    const datesWeek = @json($weeklyRevenues->pluck('week'));
    const totalsWeek = @json($weeklyRevenues->pluck('total_revenue'));
    const months = @json($monthlyRevenues->pluck('month'));
    const totalMonths = @json($monthlyRevenues->pluck('total_revenue'));
    const years = @json($yearlyRevenues->pluck(''));
     const totalYears = @json($yearlyRevenues->pluck('total_revenue'));
     // Créez les catégories dynamiques pour les semaines
const categoriesWeek = datesWeek.map(function(week) {
    return 'Semaine ' + week; // Ajoute "Semaine X" pour chaque numéro de semaine
});

document.addEventListener("DOMContentLoaded", function () {
    // Options pour chaque type de graphique
var optionsDay = {
    series: [
        {
            name: "Revenus par période",
            data: totalsDaily, 
        },
    ],
    chart: {
        height: 350,
        type: "radar",
    },
    plotOptions: {
        radar: {
            size: 120,
            polygons: {
                strokeColor: "#C9D7DD",
                fill: {
                    colors: ["#F5DAD2", "#FEF9D9"],
                },
            },
        },
    },
    xaxis: {
        categories: datesDaily,
    },
    fill: {
        opacity: 0.6,
    },

    yaxis: {
        labels: {
            formatter: function (val) {
                return val + " Ar"; // Ajouter "Ariary" à chaque valeur sur l'axe Y
            },
        },
    },
};

var optionsWeek = {
    series: [
        {
            name: "Revenus par période",
            data: totalsWeek, 
        },
    ],
    chart: {
        height: 350,
        type: "line",
    },
    xaxis: {
        categories: categoriesWeek,
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return val + " Ar"; // Ajouter "Ariary" à chaque valeur sur l'axe Y
            },
        },
    },
};

var optionsMonth = {
    series: [
        {
            name: "Revenus par période",
            data: totalMonths, 
        },
    ],
    chart: {
        height: 350,
        type: "bar",
    },

    xaxis: {
        categories: months,
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return val + " Ar"; // Ajouter "Ariary" à chaque valeur sur l'axe Y
            },
        },
    },
};

var optionsYear = {
    series: [
        {
            name: "Revenus par période",
            data: totalYears, // Exemple de données pour chaque année
        },
    ],
    chart: {
        height: 350,
        type: "area",
    },
    xaxis: {
        categories: ['2024','2025' ],
    },
    yaxis: {
        labels: {
            formatter: function (val) {
                return val + " Ar"; // Ajouter "Ariary" à chaque valeur sur l'axe Y
            },
        },
    },
};

// Initialisation pour "Revenus"
var chartRev = new ApexCharts(document.querySelector("#chartRev"), optionsDay);
chartRev.render();

document.getElementById("dayBtnRev").addEventListener("click", function () {
    chartRev.updateOptions(optionsDay, true);
});

document.getElementById("weekBtnRev").addEventListener("click", function () {
    chartRev.updateOptions(optionsWeek, true);
});

document.getElementById("monthBtnRev").addEventListener("click", function () {
    chartRev.updateOptions(optionsMonth, true);
});

document.getElementById("yearBtnRev").addEventListener("click", function () {
    chartRev.updateOptions(optionsYear, true);
});

// Activation pour les boutons Revenus
const btnRev = document.querySelectorAll(".chart-rev button");
btnRev.forEach((item) => {
    item.addEventListener("click", function (e) {
        e.preventDefault();
        btnRev.forEach((link) => link.classList.remove("active"));
        item.classList.add("active");
    });
});

});


</script>
  <script>
 // Préparer les données
 const datesDail = @json($dailyEvolution->pluck('day'));
    const totalsDail = @json($dailyEvolution->pluck('count'));
    const datesWee = @json($weeklyEvolution->pluck('week'));
    const totalsWee = @json($weeklyEvolution->pluck('count'));
    const month = @json($monthlyEvolution->pluck('month'));
    const totalMonth = @json($monthlyEvolution->pluck('count'));
    const year = @json($yearlyEvolution->pluck('year'));
    const totalYear = @json($yearlyEvolution->pluck('count'));
    const categoriesWee = datesWee.map(function(week) {
    return 'Semaine ' + week; // Ajoute "Semaine X" pour chaque numéro de semaine
});

document.addEventListener("DOMContentLoaded", function () {

    // Options pour chaque type de graphique
var optionsDays = {
    series: [
        {
            name: "Evolution de recharge ",
            data: totalsDail, // Données pour chaque jour de la semaine
        },
    ],
    chart: {
        height: 350,
        type: "radar",
    },
    plotOptions: {
        radar: {
            size: 120,
            polygons: {
                strokeColor: "#FFFBDA",
                fill: {
                    colors: ["#FFDB5C", "#FCFFE0"],
                },
            },
        },
    },
    xaxis: {
        categories: datesDail,
    },
    fill: {
        opacity: 0.6,
    },

};

var optionsWeeks = {
    series: [
        {
            name: "Evolution de recharge ",
            data: totalsWee, // Exemple de données pour chaque semaine
        },
    ],
    chart: {
        height: 350,
        type: "line",
    },
    xaxis: {
        categories: categoriesWee,
    }
};

var optionsMonths = {
    series: [
        {
            name: "Evolution de recharge ",
            data: totalMonth, 
        },
    ],
    chart: {
        height: 350,
        type: "bar",
    },

    xaxis: {
        categories: month,
    }
};

var optionsYears = {
    series: [
        {
            name: "Evolution de recharge ",
            data: totalYear,
        },
    ],
    chart: {
        height: 350,
        type: "area",
    },
    xaxis: {
        categories: year,
    }
};


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
  

</script>

@endsection