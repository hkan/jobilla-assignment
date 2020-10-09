import { format, parseISO } from 'date-fns';
import Vue from 'vue';

Vue.filter('date', (value, dateFormat) => {
  try {
    return format(parseISO(value), dateFormat);
  } catch (e) {
    console.error(e);
    return value;
  }
});
