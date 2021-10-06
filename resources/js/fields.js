import IndexText from "./components/Fields/Index/Text";
import IndexDateTime from "./components/Fields/Index/DateTime";

export default function (app) {
  app.component('index-field-text', IndexText)
  app.component('index-field-datetime', IndexDateTime)
}
