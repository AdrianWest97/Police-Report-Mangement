import Api from './Api'

export default {
//get all
  getAll() {
    return Api().get('/missing/all');
  },
  //create
  create(form) {
    return Api().post('/missing/create', form, {
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
