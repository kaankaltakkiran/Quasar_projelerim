import enUS from './en-US';

export default {
  'en-US': enUS,
};

/* eslint-disable */
import Vue from 'vue';
declare module '@vue/runtime-core' {
  export interface ComponentCustomProperties {
    $t: (key: string, ...args: any[]) => string;
  }
}
