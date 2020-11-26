import Api from './Api'

export default {
  chartByType() {
    return Api().get('/chart-by-type');
  },
    chartByParish() {
    return Api().get('/chart-by-parish');
  }
}
