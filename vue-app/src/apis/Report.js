import Api from './Api'

export default {
  reportTypes (form) {
    return Api().get('/reportTypes')
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
  }
}
