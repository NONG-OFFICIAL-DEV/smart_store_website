<template>
  <v-navigation-drawer v-model="drawer">
    <template v-slot:prepend>
      <v-list>
        <v-list-item>
          <h1>Logo</h1>
          <v-img class="image" rounded  rounded-sm></v-img>
        </v-list-item>
      </v-list>
    </template>
    <v-list dense>
      <v-list-item
        v-for="(item, index) in menu"
        :key="index"
        link
        :to="item.path"
        exact
        color="primary"
      >
        <template v-slot:prepend>
          <v-icon :icon="item.icon"></v-icon>
        </template>
        <v-list-item-title>{{ item.title }}</v-list-item-title>
      </v-list-item>
    </v-list>
    <template v-slot:append>
      <div class="pa-2 text-center">
        <v-btn
          block
          class="text-none bg-primary"
          variant="tonal"
          prepend-icon="mdi-application-outline"
        >
          Version 0.0.1
        </v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>

<script>
  export default {
    data: () => ({
      menu: [
        {
          path: '/list-user',
          title: "User Management",
          icon: 'mdi-account'
        }
      ]
    }),
    methods: {
      drawer() {
        return this.value
      }
    }
  }
</script>
