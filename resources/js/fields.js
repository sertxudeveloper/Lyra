import IndexId from "./components/Fields/Index/Id"
import IndexText from "./components/Fields/Index/Text"
import IndexDateTime from "./components/Fields/Index/DateTime"
import IndexBoolean from "./components/Fields/Index/Boolean"
import IndexImage from "./components/Fields/Index/Image"
import IndexSlug from "./components/Fields/Index/Slug"

import FormId from "./components/Fields/Form/Id"
import FormText from "./components/Fields/Form/Text"
import FormPassword from "./components/Fields/Form/Password"
import FormDateTime from "./components/Fields/Form/DateTime"
import FormBoolean from "./components/Fields/Form/Boolean"
import FormImage from "./components/Fields/Form/Image"
import FormSlug from "./components/Fields/Form/Slug"
import FormTextarea from "./components/Fields/Form/Textarea"

import DetailId from "./components/Fields/Detail/Id"
import DetailText from "./components/Fields/Detail/Text"
import DetailPassword from "./components/Fields/Detail/Password"
import DetailDateTime from "./components/Fields/Detail/DateTime"
import DetailBoolean from "./components/Fields/Detail/Boolean"
import DetailImage from "./components/Fields/Detail/Image"
import DetailSlug from "./components/Fields/Detail/Slug"
import DetailTextarea from "./components/Fields/Detail/Textarea"

export default function (app) {
  /**
   * Index fields
   */
  app.component('index-field-id', IndexId)
  app.component('index-field-text', IndexText)
  app.component('index-field-datetime', IndexDateTime)
  app.component('index-field-boolean', IndexBoolean)
  app.component('index-field-image', IndexImage)
  app.component('index-field-slug', IndexSlug)

  /**
   * Forms' fields
   */
  app.component('form-field-id', FormId)
  app.component('form-field-text', FormText)
  app.component('form-field-password', FormPassword)
  app.component('form-field-datetime', FormDateTime)
  app.component('form-field-boolean', FormBoolean)
  app.component('form-field-image', FormImage)
  app.component('form-field-slug', FormSlug)
  app.component('form-field-textarea', FormTextarea)

  /**
   * Details fields
   */
  app.component('detail-field-id', DetailId)
  app.component('detail-field-text', DetailText)
  app.component('detail-field-password', DetailPassword)
  app.component('detail-field-datetime', DetailDateTime)
  app.component('detail-field-boolean', DetailBoolean)
  app.component('detail-field-image', DetailImage)
  app.component('detail-field-slug', DetailSlug)
  app.component('detail-field-textarea', DetailTextarea)
}
