<!-- Main --->
<div class="flex flex-col h-full w-full">
  <!-- Navbar --->
  <div class="bg-white flex flex-shrink-0 h-16 shadow">
    <div class="w-full flex text-gray-500">
      <div class="flex flex-shrink-0 items-center p-5">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.8633 18.3164L15.1211 13.5742C15.0312 13.4844 14.9141 13.4375 14.7891 13.4375H14.2734C15.5039 12.0117 16.25 10.1562 16.25 8.125C16.25 3.63672 12.6133 0 8.125 0C3.63672 0 0 3.63672 0 8.125C0 12.6133 3.63672 16.25 8.125 16.25C10.1562 16.25 12.0117 15.5039 13.4375 14.2734V14.7891C13.4375 14.9141 13.4883 15.0312 13.5742 15.1211L18.3164 19.8633C18.5 20.0469 18.7969 20.0469 18.9805 19.8633L19.8633 18.9805C20.0469 18.7969 20.0469 18.5 19.8633 18.3164ZM8.125 14.375C4.67188 14.375 1.875 11.5781 1.875 8.125C1.875 4.67188 4.67188 1.875 8.125 1.875C11.5781 1.875 14.375 4.67188 14.375 8.125C14.375 11.5781 11.5781 14.375 8.125 14.375Z"/></svg>
      </div>
      <div class="text-gray-700 w-full h-full">
        <input type="search" name="search" placeholder="Search..." class="placeholder-gray-500 w-full h-full">
      </div>
    </div>
    <div class="flex flex-shrink-0 h-full items-center justify-end mr-8 text-gray-700 cursor-pointer">
      <div class="h-full overflow-hidden p-3 rounded-full">
        <img src="{{ asset(config('lyra.routes.api.prefix').'/assets/images/avatar.png') }}" alt="Sergio Peris" class="h-full rounded-full">
      </div>
      <div>Sergio Peris</div>
      <div class="m-3">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M15.8729 4.24957L15.1589 3.5337C14.9894 3.36377 14.7154 3.36377 14.5459 3.5337L8.01562 10.0669L1.48533 3.5337C1.31585 3.36377 1.0418 3.36377 0.872327 3.5337L0.158358 4.24957C-0.0111194 4.41949 -0.0111194 4.69427 0.158358 4.8642L7.70912 12.4351C7.8786 12.605 8.15265 12.605 8.32213 12.4351L15.8729 4.8642C16.0424 4.69427 16.0424 4.41949 15.8729 4.24957Z"/></svg>
      </div>
    </div>
  </div>

  <!-- Content --->
  <div class="h-full overflow-y-auto p-4 w-full">
    <router-view></router-view>
  </div>
</div>
