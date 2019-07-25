<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/css/fontawesome-all.css')}}">
    <link rel="stylesheet" href="{{asset('/css/template/bootadmin.css')}}">


    <title>MGR Techno</title>
</head>
<body class="bg-light">
<div id="app">
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            app
            clipped
        >
            <v-list dense>
                <v-list-group
                    prepend-icon="account_circle"
                    value="true"
                >
                    <template v-slot:activator>
                        <v-list-item-title>Users</v-list-item-title>
                    </template>

                    <v-list-group no-action sub-group value="true">
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title>Admin</v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <v-list-item
                            v-for="(admin, i) in admins"
                            :key="i"
                            link
                        >
                            <v-list-item-title v-text="admin[0]"></v-list-item-title>
                            <v-list-item-icon>
                                <v-icon v-text="admin[1]"></v-icon>
                            </v-list-item-icon>
                        </v-list-item>
                    </v-list-group>
                </v-list-group>
                <v-list-group prepend-icon="all_inbox"
                    value="true">
                    <template v-slot:activator>
                        <v-list-item-title>Stock</v-list-item-title>
                    </template>
                    <v-list-item v-for="(stocks, i) in stock" :key="i" link>
                        <v-list-item-title v-text="stocks[0]"></v-list-item-title>
                        <v-list-item-icon>
                            <v-icon v-text="stocks[1]"></v-icon>
                        </v-list-item-icon>
                    </v-list-item>

                </v-list-group>
            </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      clipped-left
    >
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>MGR Techno</v-toolbar-title>
    </v-app-bar>

    <v-content>
        <v-container
            fluid
            fill-height
        >
            <v-layout
                align-center
                justify-center
            >
                @yield('content')
                <!--<v-flex shrink>
                    <v-tooltip right>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          href=""
                          icon
                          large
                          target="_blank"
                          v-on="on"
                        >
                          <v-icon large>mdi-code-tags</v-icon>
                        </v-btn>
                      </template>
                      <span>Source</span>
                    </v-tooltip>
                    <v-tooltip right>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                icon
                                large
                                href="https://codepen.io/johnjleider/pen/bXNzZL"
                                target="_blank"
                                v-on="on"
                            >
                                <v-icon large>mdi-codepen</v-icon>
                            </v-btn>
                        </template>
                        <span>Codepen</span>
                    </v-tooltip>
                </v-flex>-->
            </v-layout>
        </v-container>
    </v-content>

    <v-footer app>
        <span>&copy; 2019</span>
    </v-footer>
  </v-app>

</div>
</body>
<script type="text/javascript" src="{{mix('js/app.js')}} "></script>
</html>


