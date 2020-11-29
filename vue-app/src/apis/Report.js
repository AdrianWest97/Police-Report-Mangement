import Api from './Api'

export default {
  reportTypes (form) {
    return Api().get('/reportTypes')
  },

  reportById (id) {
    return Api().get('/report/'+id)
  },

  reports () {
    return Api().get('/all')
  },

  getEdit(id) {
    return Api().get('/edit/'+id)
  },

    update(form,id) {
    return Api().put('/update/'+id,form)
  },

   delete (id) {
    return Api().delete('/delete/'+id)
  },

  deleteWitness(id) {
    return Api().delete('/delete-witness/'+id)
  },
  //admin
  getAll() {
     return Api().get('/admin/all-reports')
   },
  //update report status
  updateStatus(form) {
    return Api().put('/admin/update-status', form);
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
  }
}
