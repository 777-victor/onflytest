<template>
  <div class="q-pa-md">
    <q-table
      title="Expenses"
      :rows="rows"
      :columns="columns"
      row-key="description"
    >
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
        <q-btn color="primary" :icon="'add'" @click="redirecToCreateExpense">
          <q-tooltip v-close-popup> {{ "Add new expense" }}</q-tooltip>
        </q-btn>

        <!-- <q-btn
          flat
          round
          dense
          :icon="isGrid ? 'list' : 'grid_on'"
          @click="isGrid = !isGrid"
        >
          <q-tooltip :disable="$q.platform.is.mobile" v-close-popup>{{
            isGrid ? "List" : "Grid"
          }}</q-tooltip>
        </q-btn> -->
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn
            dense
            round
            flat
            color="grey"
            @click="editExpense(props)"
            icon="edit"
          >
            <q-tooltip v-close-popup> Edit expense </q-tooltip>
          </q-btn>
          <q-btn
            dense
            round
            flat
            color="grey"
            @click="deleteExpense(props)"
            icon="delete"
          >
            <q-tooltip v-close-popup> Remove expense </q-tooltip>
          </q-btn>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script>
import { get } from "../../helpers/request";

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
        { name: "actions", label: "Actions", field: "", align: "center" },
        // {
        //   name: "action",
        //   align: "center",
        //   label: "Action",
        //   field: "",
        // },
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

  mounted() {
    this.listExpenses();
  },
  methods: {
    redirecToCreateExpense() {
      // const router = useRouter();
      this.$router.push({ path: "/expenses/create" });
    },
    listExpenses() {
      get("expenses").then((response) => {
        const { data } = response;
        this.rows = data.expenses;
      });
    },

    editExpense(prop) {
      let item = prop.row;
      this.$router.push({ path: "/expenses/edit/" + item.id });
    },

    deleteExpense(item) {
      //sweet alert
      // alert()
      // this.$router.push({ path: "/expenses/edit/" + item.id });
    },
  },
};
</script>
