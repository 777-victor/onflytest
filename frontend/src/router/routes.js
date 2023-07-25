const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "", component: () => import("pages/IndexPage.vue") }],
  },

  {
    name: "auth",
    path: "/auth",
    component: () => import("layouts/LoginLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/auth/LoginPage.vue"),
      },
    ],
  },

  {
    path: "/register",
    component: () => import("layouts/LoginLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/auth/RegisterPage.vue"),
      },
    ],
  },

  {
    path: "/expenses",
    component: () => import("layouts/MainLayout.vue"),
    children: [
      {
        path: "",
        component: () => import("pages/expense/ExpenseList.vue"),
      },
      {
        path: "create",
        component: () => import("pages/expense/ExpenseForm.vue"),
      },
      {
        path: "edit/:id",
        component: () => import("pages/expense/ExpenseForm.vue"),
      },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
