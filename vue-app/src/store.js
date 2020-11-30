import Vue from 'vue'
import Vuex from 'vuex'
import Report from './apis/Report'
import MissingPersons from './apis/MissingPerson'
import createPersistedState from 'vuex-persistedstate'


Vue.use(Vuex)

export default new Vuex.Store({
    // plugins: [createPersistedState({
    //     storage: window.sessionStorage,
    // })],
  state: {
    showNav: false,
    num: 0,
    auth: {
      login: false,
      user: []
    },
    newReportDialog: {
      dialog:false
    },
       missingDialog: {
         dialog: false,
         mode: '',
         report:null
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

    TrackReportDialog: {
        visible:false
    },
    RespondDialog:{
      visible: false,
      report:null
    },
    AllReports: [],
    MissingPersons:[]
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
        missingReportDialog(state) {
      return state.missingDialog;
    },
    successDialog(state) {
      return state.successDialog;
    },
    getSnack(state) {
      return state.snackBar
    },
    getEditReport(state) {
      return state.EditReportDialog;
    },
       getRespondDialog(state) {
      return state.RespondDialog
    },
    getAllReports(state) {
      return state.AllReports;
    },
    getAllMissing(state) {
      return state.MissingPersons
    }
  },
  mutations: {
    LOGIN(state, status) {
      state.auth.login = status
      state.auth.user = []
    },

    AUTH_USER(state, user) {
      state.auth.user = user
    },
    REPORT_TYPES(state, data) {
      state.reportTypes = data;
    },
    SET_REPORT_DIALOG(state, dialog) {
      state.newReportDialog = {
        dialog: dialog
      }
    },

      SET_MISSING_REPORT_DIALOG(state, payload) {
      state.missingDialog = {
        dialog: payload.dialog,
        mode: payload.mode,
        report: payload.report
      }
    },

    SET_TRACK_REPORT_DIALOG(state, dialog) {
      state.TrackReportDialog = {
        visible: dialog
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
        visible: data.visible
      }
    },
    SET_RESPOND_DIALOG(state, data) {
      state.RespondDialog = {
        report: data.report,
        visible: data.visible
      }
    },
    SET_ALL_REPORTS(state, data) {
      state.AllReports = data;
    },

    EDIT_REPORT(state, payload) {
      var index = state.AllReports.findIndex(report => report.id == payload.id)
      state.AllReports[index] = payload
    },

    SET_ALL_MISSING(state, payload) {
      state.MissingPersons = payload;
    },
    ADD_MISSING_REPORT(state, payload) {
      if (payload.mode == 'add') {
        state.MissingPersons = [...state.MissingPersons, payload.report];
      } else {
        //edit
        var index = state.MissingPersons.findIndex(report => report.id == payload.report.id)

        Vue.set(state.MissingPersons,index,payload.report)

      }
    }
  },
  actions: {
         showTrackReportDialog({commit}){
        commit("SET_TRACK_REPORT_DIALOG",true)
    },

   fetchAllReports({ commit }) {
       Report.getAll()
           .then((res) => {
             commit("SET_ALL_REPORTS", res.data.reports);
      })
    },
      fetchAllMissing({ commit }) {
       MissingPersons.getAll()
           .then((res) => {
             commit("SET_ALL_MISSING", res.data);
      })
    },


    editReport({ commit },report) {
      commit("EDIT_REPORT",report)
    }


  }
})
