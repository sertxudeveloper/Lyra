import IndexId from "./components/Fields/Index/Id"
import IndexText from "./components/Fields/Index/Text"
import IndexDateTime from "./components/Fields/Index/DateTime"

import FormId from "./components/Fields/Form/Id"
import FormText from "./components/Fields/Form/Text"
import FormDateTime from "./components/Fields/Form/DateTime"

export default function (app) {
  /**
   * Index fields
   */
  app.component('index-field-id', IndexId)
  app.component('index-field-text', IndexText)
  app.component('index-field-datetime', IndexDateTime)

  /**
   * Forms' fields
   */
  app.component('form-field-id', FormId)
  app.component('form-field-text', FormText)
  app.component('form-field-datetime', FormDateTime)
}
