import Vue from 'vue'
import Vuex from 'vuex'
import Report from './apis/Report'
import createPersistedState from 'vuex-persistedstate'


Vue.use(Vuex)

export default new Vuex.Store({
    plugins: [createPersistedState({
        storage: window.sessionStorage,
    })],
  state: {
    showNav: false,
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

    TrackReportDialog: {
        visible:false
    },
    RespondDialog:{
      visible: false,
      report:null
    },
    AllReports:null
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
    },
       getRespondDialog(state) {
      return state.RespondDialog
    },
    getAllReports(state) {
      return state.AllReports;
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
        SET_TRACK_REPORT_DIALOG(state, dialog) {
      state.TrackReportDialog= {
        visible:dialog
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
    },
        SET_RESPOND_DIALOG(state, data) {
      state.RespondDialog = {
        report:data.report,
        visible:data.visible
      }
    },
    SET_ALL_REPORTS(state, data) {
      return state.AllReports = data;
        }

  },

  actions: {
         showTrackReportDialog({commit}){
        commit("SET_TRACK_REPORT_DIALOG",true)
    },

    fetchAllReports({ commit }) {
         Report.getAll()
           .then((res) => {
          commit("SET_ALL_REPORTS", {
            AllReports:res.data.reports
          })
      })
    },

    // showRespondDialog({ commit }, id) {
    //   Report.reportById(id)
    //     .then((res) => {
    //       commit("SET_RESPOND_DIALOG", {
    //         visible: true,
    //         report:res.data.report
    //       })
    //   })
    //   }
  }
})
