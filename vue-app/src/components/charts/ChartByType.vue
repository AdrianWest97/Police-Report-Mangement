<script>
import {Doughnut} from 'vue-chartjs'
import Charts from '../../apis/Charts.js'
import ChartDataLabels from 'chartjs-plugin-datalabels';

export default {
  extends: Doughnut,
  data(){
    return{
      ChartData:null,
              options:{
             responsive: true,
             maintainAspectRatio: false,
             title: {
            display: true,
            text: 'Report based on type',
            fontSize:20
        },
          tooltips: {
         enabled: false
    },
          plugins: {
       datalabels: {
        color: '#fff',
        display: true,
        formatter: function (value, ctx) {
         return value > 0 ? value : ''
        },
      },
        },
         legend: {
            labels: {
                // This more specific font property overrides the global property
                fontColor: 'black'
            }
        }

    }
    }
  },

  methods:{
    fetchData(){

      Charts.chartByType()
      .then((res)=>{
         this.chartData = {
          labels: res.data.data.map(type=>type.type),
          datasets:[{
            label: res.data.data.map(type=>type.type),
            data:res.data.data.map(type=>type.reports_count),
              backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86,1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
              ]
          }],
        }
      this.plotGraph();
      })
    },
    plotGraph(){
         this.renderChart(this.chartData, this.options)
  },
  },
  mounted () {
    this.fetchData();


  }
}
</script>
