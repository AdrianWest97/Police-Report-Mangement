import Api from './Api'


//guest
export default {
  reportTypes (form) {
    return Api().get('/reportTypes')
  },

  reportById (id) {
    return Api().get('/report/report/'+id)
  },

  reports () {
    return Api().get('/report/all')
  },

  getEdit(id) {
    return Api().get('/report/edit/'+id)
  },

    update(form,id) {
    return Api().put('/report/update/'+id,form)
  },

   delete (id) {
    return Api().delete('/report/delete/'+id)
  },

  deleteWitness(id) {
    return Api().delete('/report/delete-witness/'+id)
  },
  getParishStatistic(parish) {
    return Api().get('/parish_statistic/'+parish)
  },


  //admin
  getAll() {
     return Api().get('/admin/all-reports')
   },
  //update report status
  updateStatus(form) {
    return Api().put('/admin/update-status', form);
  },

    //update report status
  updateMissingStatus(form) {
    return Api().put('/admin/update-missing-status', form);
  },

  reportByTypeChart() {
    return Api().get('/report-by-type');
  },

  getCardData() {
    return Api().get('/admin/card-data');
  },
  //active users
getActiveUsers() {
  return Api().get('/admin/active-users');
  },
  getStatus($ref) {
    return Api().get('/status/'+$ref)
  },




}
