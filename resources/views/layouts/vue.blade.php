<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <title>@yield('title', 'Books Library')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <noscript>
      <strong>We're sorry but <%= htmlWebpackPlugin.options.title %> doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app">
        <v-app id="inspire">
            <v-navigation-drawer
                v-model="drawer"
                app
            >
                <div class="sidebar-menu">
                    <v-list>
                        <v-list-item>
                            <a class="btn btn-primary" href="/">Books</a>
                        </v-list-item>
                        <v-list-item>
                            <a class="btn btn-primary" href="/upload">Books upload</a>
                        </v-list-item>
                    </v-list>
                </div>
            </v-navigation-drawer>

            <v-app-bar
              app
            >
              <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
              <v-toolbar-title><h2 title="@yield('title', 'Books Library')">@yield('title', 'Books Library')</h2></v-toolbar-title>
            </v-app-bar>

            <v-main>
              <v-container
                fluid
                fill-height
              >
                <v-layout>
                  <v-flex>
                    @yield('content')
                  </v-flex>
                </v-layout>
              </v-container>
            </v-main>
            <v-footer
              app
            >
              <span>&copy; {{ date('Y') }}</span>
            </v-footer>
        </v-app>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
