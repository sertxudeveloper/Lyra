import FilterMenu from "./components/Resources/FilterMenu";
import Datatable from "./components/Resources/Datatable";
import DatatableRow from "./components/Resources/DatatableRow";
import Loading from "./components/Resources/Loading";

import NotificationWrapper from "./components/Notifications/Wrapper";

import Dropdown from "./components/Dropdown/Dropdown";
import DropdownMenu from "./components/Dropdown/DropdownMenu";

import Pagination from "./components/Pagination/Pagination";

export default function (app) {
  app.component('FilterMenu', FilterMenu)
  app.component('Datatable', Datatable)
  app.component('DatatableRow', DatatableRow)
  app.component('Loading', Loading)

  app.component('notification-wrapper', NotificationWrapper)

  app.component('Dropdown', Dropdown)
  app.component('DropdownMenu', DropdownMenu)

  app.component('Pagination', Pagination)

  app.config.globalProperties.$notify = (options) => {
    app._instance.refs['notification-wrapper'].add(options)
  }
}
