<template>
  <div class="q-pa-md">
    <q-table
      :grid="grid"
      class="statement-table"
      virtual-scroll
      flat
      bordered
      title="Expenses"
      :rows="rows"
      :columns="columns"
      row-key="id"
      :loading="loading"
      v-model:pagination="pagination"
      @request="listExpenses"
    >
      <template v-slot:top-right="">
        <q-btn color="primary" :icon="'add'" @click="redirecToCreateExpense">
          <q-tooltip v-close-popup> {{ "Add new expense" }}</q-tooltip>
        </q-btn>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props">
          <q-btn
            dense
            round
            flat
            color="primary"
            @click="editExpense(props)"
            icon="edit"
          >
            <q-tooltip v-close-popup> Edit expense </q-tooltip>
          </q-btn>
          <q-btn
            dense
            round
            flat
            color="negative"
            @click="deleteExpense(props)"
            icon="delete"
          >
            <q-tooltip v-close-popup> Remove expense </q-tooltip>
          </q-btn>
        </q-td>
      </template>

      <template v-slot:item="props">
        <div
          class="q-pa-xs col-xs-12 col-sm-6 col-md-4 col-lg-3 grid-style-transition"
          :style="props.selected ? 'transform: scale(0.95);' : ''"
        >
          <q-card :class="props.selected ? 'bg-grey-2' : ''">
            <q-list dense>
              <q-item v-for="col in props.cols" :key="col.name">
                <q-item-section>
                  <q-item-label>{{ col.label }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                  <div v-if="col.name === 'actions'">
                    <q-btn
                      dense
                      round
                      flat
                      color="primary"
                      @click="editExpense(props)"
                      icon="edit"
                    >
                      <q-tooltip v-close-popup> Edit expense </q-tooltip>
                    </q-btn>
                    <q-btn
                      dense
                      round
                      flat
                      color="negative"
                      @click="deleteExpense(props)"
                      icon="delete"
                    >
                      <q-tooltip v-close-popup> Remove expense </q-tooltip>
                    </q-btn>
                  </div>
                  <q-item-label
                    v-else
                    caption
                    :class="col.classes ? col.classes : ''"
                    >{{ col.value }}</q-item-label
                  >
                </q-item-section>
              </q-item>
            </q-list>
          </q-card>
        </div>
      </template>
    </q-table>
  </div>
</template>

<script>
import { useQuasar } from "quasar";
import { ref, onMounted, computed, defineComponent } from "vue";
import { useRouter } from "vue-router";
import { get, remove } from "../../helpers/request";
import Swal from "sweetalert2";

export default defineComponent({
  name: "RegisterPage",
  setup(props, { attrs, slots, emit }) {
    const $q = useQuasar();
    const router = useRouter();
    const loading = ref(false);
    const pagination = ref({
      sortBy: "desc",
      descending: false,
      page: 1,
      rowsPerPage: 5,
      rowsNumber: 10,
    });
    const columns = [
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
    ];

    const rows = ref(Array());

    const grid = computed({
      get: (_) => $q.screen.xs,
      set: (v) => {
        emit("update:grid", v);
      },
    });

    onMounted(() => {
      listExpenses();
    });

    function listExpenses(props) {
      loading.value = true;
      let { page, rowsPerPage } = pagination.value;

      if (props) {
        page = props.pagination.page;
        rowsPerPage = props.pagination.rowsPerPage;
      }

      get(`expenses?page=${page}&perPage=${rowsPerPage}`)
        .then((response) => {
          const { data } = response;
          rows.value = data.expenses;

          const meta = data.meta;
          pagination.value.page = meta.current_page;
          pagination.value.rowsPerPage = meta.per_page;
          pagination.value.rowsNumber = meta.total;
        })
        .finally(() => {
          loading.value = false;
        });
    }

    function redirecToCreateExpense() {
      router.push({ path: "/expenses/create" });
    }

    function editExpense(prop) {
      let item = prop.row;
      router.push({ path: "/expenses/edit/" + item.id });
    }

    function deleteExpense(prop) {
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
          handleDeleteExpense(item.id);
        }
      });
    }

    function handleDeleteExpense(id) {
      remove("expenses/" + id)
        .then((response) => {
          $q.notify({
            message: response?.data?.message ?? "Expense deleted sucessfully",
            color: "positive",
            icon: "check_circle_outline",
          });
        })
        .catch((error) => {
          let message =
            error.response?.message || "Failed to delete this expense";

          $q.notify({
            message: message,
            color: "negative",
            icon: "error",
          });
        })
        .finally(() => {
          listExpenses();
        });
    }

    return {
      $q,
      loading,
      pagination,
      columns,
      rows,
      grid,
      listExpenses,
      redirecToCreateExpense,
      editExpense,
      deleteExpense,
    };
  },
});
</script>

<style lang="sass">
.grid-style-transition
  transition: transform .28s, background-color .28s
</style>
