class ApiConfig {

  static const bool isProduction = false;

  static String get baseUrl {
    if (isProduction) {
      return "https://api.macbi.com/api";
    } else {
      return "http://172.19.139.115:8000/api";
    }
  }

}
