import Api from './Api'

export default {
//get all
  getAll() {
    return Api().get('/missing/all');
  },
  //create
  create(form,mode,id) {
    return Api().post('/missing/create/'+mode+'/'+id, form, {
       headers:{
            'Content-Type': 'multipart/form-data'
          }
    });
  },
  deleteReport(id) {
    return Api().delete('/missing/delete/' + id);
  },

  getImage(image) {
    return Api().get('/load_image/'+image)
  }
}
