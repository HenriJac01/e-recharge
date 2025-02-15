@extends('layouts/dgi')
@section('content')
<h2 class="title">Tableau de bord</h2>
        <ul class="breadcrumbs">
          <li><a href="">Accueil</a></li>
          <li><a href="" class="divider">/</a></li>
          <li><a href="" class="active">Tableau de bord</a></li>
        </ul>
        <div class="dashboard-container">
          <div class="main-content">
            <!-- Colonne des opérateurs -->
            <div class="operator-column">
              <div class="operator-card">
                <img src="{{ asset('img/yas.jpg') }}" alt="Telma" class="operator-logo" />
                <div class="operator-info">
                  <p class="operator-name">Yas</p>
                  <p class="operator-revenue">{{ number_format($totalCaYas, 0, ',', ' ') }} Ar</p>
                </div>
              </div>
              <div class="operator-card">
                <img src="{{ asset('img/orange.jpg') }}" alt="Orange" class="operator-logo" />
                <div class="operator-info">
                  <p class="operator-name">Orange</p>
                  <p class="operator-revenue">{{ number_format($totalcaOrange, 0, ',', ' ') }} Ar</p>
                </div>
              </div>
              <div class="operator-card">
                <img src="{{ asset('img/airtel.png') }}" alt="Airtel" class="operator-logo" />
                <div class="operator-info">
                  <p class="operator-name">Airtel</p>
                  <p class="operator-revenue">{{ number_format($totalcaAirtel, 0, ',', ' ') }} Ar</p>
                </div>
              </div>
            </div>

            <!-- Section Graphique -->
            <div class="chart-card">
              <h3>Recettes mensuels</h3>
              <div id="bar-chart"></div>
            </div>

            <!-- Colonne Balance et Taxes -->
        
            <div class="right-column">
              <div class="card-a">
                <h3>Balance</h3>
                <p>{{ number_format($totalCab, 0, ',', ' ') }} Ar</p>
              </div>
              <div class="card-b">
                <h3>Taxes</h3>
                <p>{{ number_format($totatTaxe, 0, ',', ' ') }} Ar</p>
                </div>
            </div>
          </div>
        </div>

        <!-- Graphiques -->
        <section id="tax-charts" class="charts-grid">
          <div class="charts-card">
            <h3>Répartition des taxes</h3>
            <div id="column-chart"></div>
          </div>

          <div class="charts-card">
            <h3>Comparaison des opérateurs</h3>
            <div id="stacked-chart"></div>
          </div>
          <div class="charts-card">
            <h3>Taxes annuelles collectées</h3>
            <div id="pie-chart"></div>
          </div>
          <div class="charts-card">
            <h3>Taxes globales</h3>
            <div id="area-chart"></div>
          </div>
        </section>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Passer les données depuis Laravel à JavaScript
    const chartData = @json($chartData);

    // Extraire les labels (nom des opérateurs) et les valeurs (total des taxes)
    const operatorTaxe = chartData.map(item => item.operatorTaxe);  // Labels
    const taxation = chartData.map(item => item.taxation);     // Valeurs

    // Définir des couleurs spécifiques pour chaque opérateur
    const colors = operatorTaxe.map(operator => {
        switch (operator) {
            case 'Yas': 
                return ['#FFD700', '#FFA500']; // Jaune & Orange
            case 'Orange': 
                return ['#FF4500'];  // Orange
            case 'Airtel': 
                return ['#008FFB'];  // Bleu
            default: 
                return ['#00E396'];  // Vert (par défaut)
        }
    }).flat(); // Aplatir le tableau

    // Définir un Column Chart (remplace le Donut)
    const columnChartOptions = {
        chart: {
            type: 'bar', // Type 'column' pour barres verticales
            height: 350
        },
        series: [{
            name: 'Taxes',
            data: taxation // Valeurs des taxes
        }],
        xaxis: {
            categories: operatorTaxe, // Labels des opérateurs
            title: {
                text: "Opérateurs",
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            }
        },
        yaxis: {
            title: {
                text: "Total des taxes (Ar)",
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    color: '#333'
                }
            }
        },
        colors: colors, // Appliquer les couleurs personnalisées
        title: {
             align: "center",
            style: {
                fontSize: '18px',
                fontWeight: 'bold',
                color: '#333'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString() + " Ar";  // Afficher avec séparateurs de milliers
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 10,  
                horizontal: false, 
                distributed: true, 
                dataLabels: {
                    position: "top" 
                }
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                colors: ['#000']
            }
        }
    };

    // Initialiser le graphique avec ApexCharts
    const chart = new ApexCharts(document.querySelector("#column-chart"), columnChartOptions);
    chart.render();
</script>
<script>
   const monthRecette = @json($monthlyRecette->pluck('monthRecette'));
   const totalRecette = @json($monthlyRecette->pluck('total_recette'));
   const monthYas = @json($monthlyYas->pluck('monthYas'));
   const chiAffYAs = @json($monthlyYas->pluck('chiAffYAs'));
   const chiAffOrange = @json($monthlyOrange->pluck('chiAffOrange'));
   const chiAffAirtel = @json($monthlyAirtel->pluck('chiAffAirtel'));
   const mmonthTaxeYas = @json($taxeYas->pluck('mmonthTaxeYas'));
   const taxeYas = @json($taxeYas->pluck('taxeYas'));
   const taxeOrange = @json($taxeOrange->pluck('taxeOrange'));
   const taxeAirtel = @json($taxeAirtel->pluck('taxeAirtel'));
   const taxesParAnnee = @json($taxesParAnnee).map(Number); // Taxes par année
   const annee = @json($annees); // Années

    // Ajouter une valeur par défaut si vide
    if (taxesParAnnee.length === 0) {
      taxesParAnnee.push(0);
      annee.push("Aucune donnée");
        }
    // Bar Chart - Recette mensuels
    const barChartOptions = {
  chart: {
    type: "bar",
    height: 250,
    toolbar: {
      show: false,
    },
  },
  series: [
    {
      name: "Recettes",
      data: totalRecette, // Les données du graphique
    },
  ],
  xaxis: {
    categories: monthRecette, // Catégories des mois
    labels: {
      style: {
        colors: "#6c757d",
        fontSize: "12px",
      },
    },
  },
  plotOptions: {
    bar: {
      borderRadius: 4,
      horizontal: false,
    },
  },
  colors: ["#2d93b0"],
  dataLabels: {
    enabled: false, // Pas d'étiquettes de données visibles
  },
  grid: {
    show: true,
    borderColor: "#e7e7e7",
    strokeDashArray: 5,
  },
  tooltip: {
    theme: "dark",
    y: {
      formatter: function (value) {
        return value + " Ar"; // Ajouter "Ar" au montant des recettes
      },
    },
  },
  // Ajouter l'unité "Ar" aux labels de l'axe Y
  yaxis: {
    labels: {
      formatter: function (value) {
        return value + " Ar"; // Ajouter "Ar" sur l'axe Y
      },
    },
  },
};

    const barChart = new ApexCharts(
      document.querySelector("#bar-chart"),
      barChartOptions
    );
    barChart.render();


    // 4. Pie Chart - Comparaison annuelle des taxes collectées
    const pieChartOptions = {
      chart: {
        type: "pie", // Type de graphique
      },
      series: taxesParAnnee, // Taxes collectées en milliards pour les années 2020, 2021, 2022, 2023
      labels: annee, // Années de collecte des taxes
    };

    const pieChart = new ApexCharts(
      document.querySelector("#pie-chart"),
      pieChartOptions
    );
    pieChart.render();

    // 5. Area Chart - Taxes Globales
    const areaChartOptions = {
      chart: {
        type: "area",
      },
      series: [
        {
          name: "Airtel",
          data: taxeAirtel,
        },
        {
          name: "Yas",
          data: taxeYas,
        },
        {
          name: "Orange",
          data: taxeOrange,
        },
       
      ],
      xaxis: {
        categories: mmonthTaxeYas,
      },
       // Ajouter l'unité "Ar" aux labels de l'axe Y
    yaxis: {
      labels: {
        formatter: function (value) {
          return value + " Ar"; // Ajouter "Ar" sur l'axe Y
        },
      },
    },
    };

    const areaChart = new ApexCharts(
      document.querySelector("#area-chart"),
      areaChartOptions
    );
    areaChart.render();

    // 6. Stacked Bar Chart - Comparaison des Opérateurs
    const stackedChartOptions = {
      chart: {
        type: "bar",
        stacked: true,
      },
      series: [
        {
          name: "Airtel",
          data: chiAffAirtel,
        },
        {
          name: "Yas",
          data: chiAffYAs,
        },
        {
          name: "Orange",
          data: chiAffOrange,
        },
      ],
      xaxis: {
        categories: monthYas,
      },
       // Ajouter l'unité "Ar" aux labels de l'axe Y
    yaxis: {
      labels: {
        formatter: function (value) {
          return value + " Ar"; // Ajouter "Ar" sur l'axe Y
        },
      },
    },
    };

    const stackedChart = new ApexCharts(
      document.querySelector("#stacked-chart"),
      stackedChartOptions
    );
    stackedChart.render();
</script>
@endsection