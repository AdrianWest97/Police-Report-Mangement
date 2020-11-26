<script>
import {Bar} from 'vue-chartjs'
import Charts from '../../apis/Charts.js'
import ChartDataLabels from 'chartjs-plugin-datalabels';

export default {
  extends: Bar,
  data(){
    return{
      ChartData:null,
              options:{
             responsive: true,
             maintainAspectRatio: false,
             title: {
            display: true,
            text: 'Number of reports per parish',
            fontSize:20
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                }
            }]
        }

    }
    }
  },

  methods:{
    fetchData(){

      Charts.chartByParish()
      .then((res)=>{
        console.log(Object.entries(res.data.data).map(data=>data[1]))
         this.chartData = {
          labels: Object.entries(res.data.data).map(data=>data[0]),
          datasets:[{
            label:'Number of reports per parish',
            data:Object.entries(res.data.data).map(data=>data[1]),
              backgroundColor: 'RGB(54, 162, 235)',
        barPercentage: 0.5,
        barThickness: 30,
        maxBarThickness: 30,
        minBarLength: 2,
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
