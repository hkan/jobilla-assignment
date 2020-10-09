import Vue from 'vue';
import infiniteScroll from 'vue-infinite-scroll';
import './filters/date';
import './filters/string_trim';

Vue.use(infiniteScroll);

new Vue({
  el: '#app',
  components: {
    JobList: require('./components/JobList.vue').default,
  },
  render(h) {
    return h('job-list');
  },
});
