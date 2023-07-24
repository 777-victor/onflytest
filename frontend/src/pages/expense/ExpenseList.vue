<template>
  <div class="q-pa-md">
    <q-table title="Treats" :rows="rows" :columns="columns" row-key="name">
      <template v-slot:top-right="">
        <!-- <q-input
        outlined
        dense
        debounce="300"
        v-model="filter"
        placeholder="Search"
      >
        <template v-slot:append>
          <q-icon name="search" ></icon>
        </template>
      </q-input> -->
        <q-btn flat round dense :icon="'add'" @click="redirecToCreateExpense">
          <q-tooltip v-close-popup> {{ "Add new expense" }}</q-tooltip>
        </q-btn>

        <q-btn
          flat
          round
          dense
          :icon="isGrid ? 'list' : 'grid_on'"
          @click="isGrid = !isGrid"
        >
          <q-tooltip :disable="$q.platform.is.mobile" v-close-popup>{{
            isGrid ? "List" : "Grid"
          }}</q-tooltip>
        </q-btn>
      </template>
    </q-table>
  </div>
</template>

<script>
export default {
  name: "RegisterPage",
  data() {
    return {
      submiting: false,
      columns: [
        {
          name: "description",
          label: "Description",
          align: "left",
          field: (row) => row.description,
          format: (val) => `${val}`,
          sortable: true,
        },
        {
          name: "value",
          align: "left",
          label: "Value",
          field: "value",
          sortable: true,
        },
        { name: "date", align: "left", label: "Date", field: "date" },
      ],
      rows: [
        {
          description: "gastei",
          value: 0.0,
          date: "",
        },
      ],
    };
  },
  computed: {
    // isGrid(){
    //   get: _ => attrs?.grid,
    //   set: v => { emit('update:grid', v) }
    // }
  },
  methods: {
    redirecToCreateExpense() {
      // const router = useRouter();
      this.$router.push({ path: "/expenses/create" });
    },
  },
};
</script>
