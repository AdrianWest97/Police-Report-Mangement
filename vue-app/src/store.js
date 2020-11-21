import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    auth: {
      login: false,
      user: []
    },
    newReportDialog: {
      dialog:false
    },
      successDialog: {
          visible:false,
          content:"",
          message:"",
        title: "",
        },
    reportTypes: [],
        parishes:[
        "Hanover",
        "St. Elizabeth",
        "St. James",
        "Trelawny",
        "Westmoreland",
        "Clarendon",
        "Manchester",
        "St. Ann",
        "St. Catherine",
        "St. Mary",
        "Kingston",
        "Portland",
        "St. Andrew",
        "St. Thomas"
    ],

    EditReportDialog:{
    visible: false,
      report:null
        },
         snackBar:{
        visible:false,
           content: "",
        timeout:2000
        },
  },


  getters: {
    isLoggedIn (state) {
      return state.auth.login
    },
      reportTypes(state) {
      return state.reportTypes
    },
            parishes(state) {
      return state.parishes
    },
    reportDialog(state) {
      return state.newReportDialog;
    },
    successDialog(state) {
      return state.successDialog;
    },
    getSnack(state) {
      return state.snackBar
    },
    getEditReport(state) {
      return state.EditReportDialog;
    }
  },
  mutations: {
    LOGIN (state, status) {
      state.auth.login = status
      state.auth.user = []
    },

    AUTH_USER (state, user) {
      state.auth.user = user
    },
        REPORT_TYPES (state, data) {
          state.reportTypes = data;
    },
    SET_REPORT_DIALOG(state, dialog) {
      state.newReportDialog = {
        dialog:dialog
      }
    },
    SET_SUCCESS_DIALOG(state, data) {
      state.successDialog = {
        content: data.content,
        message: data.message,
        title: data.title,
        visible: data.visible,
      }
    },
    SET_SNACK_BAR(state, data) {
      state.snackBar = {
        visible: data.visible,
        content: data.content,
        timeout: data.timeout

      }
    },
    SET_EDIT_REPORT_DIALOG(state, data) {
      state.EditReportDialog = {
        report: data.report,
        visible:data.visible
      }
    }
  },

  actions: {

  }
})
