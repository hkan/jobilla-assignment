import Vue from 'vue';

Vue.filter('trim', (value, maxLength = 100) => {
  if (value.toString().length < maxLength) {
    return value;
  }

  return value.substr(0, maxLength) + '...';
});
