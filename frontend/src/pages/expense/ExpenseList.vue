<template>
  <div class="q-pa-md">
    <q-table
      :grid="grid"
      flat
      bordered
      title="Expenses"
      :rows="rows"
      :columns="columns"
      row-key="id"
      :loading="loading"
      v-model:pagination="pagination"
      binary-state-sort
      @request="listExpenses"
    >
      <template v-slot:top-right="">
        <q-btn color="primary" :icon="'add'" @click="redirecToCreateExpense">
          <q-tooltip v-close-popup> {{ "Add new expense" }}</q-tooltip>
        </q-btn>
      </template>

      <template v-slot:body-cell-actions="props">
        <!-- <component :is="grid ? 'div' : 'q-td'" :props="props"> -->
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
        <!-- </component> -->
      </template>
    </q-table>
  </div>
</template>

<script>
import { get, remove } from "../../helpers/request";
import Swal from "sweetalert2";

export default {
  name: "RegisterPage",
  data() {
    return {
      loading: false,
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
      ],
      pagination: {
        sortBy: "desc",
        descending: false,
        page: 1,
        rowsPerPage: 3,
        rowsNumber: 10,
      },
      rows: [],
    };
  },
  computed: {
    grid() {
      return this.$q.screen.xs;
    },
  },

  mounted() {
    this.listExpenses();
  },
  methods: {
    redirecToCreateExpense() {
      this.$router.push({ path: "/expenses/create" });
    },

    listExpenses(props) {
      this.loading = true;
      let { page, rowsPerPage } = this.pagination;

      if (props) {
        page = props.pagination.page;
        rowsPerPage = props.pagination.rowsPerPage;
      }

      get(`expenses?page=${page}&perPage=${rowsPerPage}`)
        .then((response) => {
          const { data } = response;
          this.rows = data.expenses;

          const meta = data.meta;
          this.pagination.page = meta.current_page;
          this.pagination.rowsPerPage = meta.per_page;
          this.pagination.rowsNumber = meta.total;
        })
        .finally(() => {
          this.loading = false;
        });
    },

    editExpense(prop) {
      let item = prop.row;
      this.$router.push({ path: "/expenses/edit/" + item.id });
    },

    deleteExpense(prop) {
      let item = prop.row;

      Swal.fire({
        title: "Are you sure ?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Delete",
        denyButtonText: `Cancel`,
      }).then((result) => {
        if (result.isConfirmed) {
          this.handleDeleteExpense(item.id);
        }
      });
    },

    handleDeleteExpense(id) {
      remove("expenses/" + id)
        .then((response) => {
          this.$q.notify({
            message: response?.message,
            color: "positive",
            icon: "check_circle_outline",
          });
        })
        .catch((error) => {
          let message =
            error.response?.message || "Failed to delete this expense";

          this.$q.notify({
            message: message,
            color: "negative",
            icon: "error",
          });
        })
        .finally(() => {
          this.listExpenses();
        });
    },
  },
};
</script>
