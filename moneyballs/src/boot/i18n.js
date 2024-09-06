import { createI18n } from 'vue-i18n';
import messages from 'src/i18n';
//dil seçimi için LocalStorage kullanacağımız için LocalStorage'ı import ediyoruz
import { LocalStorage } from 'quasar';

export default ({ app }) => {
  // Create I18n instance
  const i18n = createI18n({
    locale: LocalStorage.getItem('locale') || 'tr', // uygulama ilk açıldığında locale değeri yoksa 'tr' olarak ayarla varsa LocalStorage'dan al
    //legacy: false, // comment this out if not using Composition API
    messages,
  });

  // Tell app to use the I18n instance
  app.use(i18n);
};
