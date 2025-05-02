import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import { Notify, Dialog } from "quasar";

const _scrollToFirstError = () => {
  const page = usePage();
  const firstErrorKey = Object.keys(page.props.errors)[0];
  if (firstErrorKey) {
    setTimeout(() => {
      const errorElement = document.querySelector('.q-field--error input');
      if (errorElement) {
        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        errorElement.focus();
      }
    }, 0);
  }
};

export function handleSubmit(data) {
  const { form, url } = data;

  form.clearErrors();
  form.post(url,
    {
      preserveScroll: true,
      onSuccess: (response) => {
        // Notify.create({
        //   message: response.message,
        //   icon: "info",
        //   color: "positive",
        //   actions: [
        //     { icon: "close", color: "white", round: true, dense: true },
        //   ],
        // });
      },
      onError: (response) => {
        _scrollToFirstError();

        if (typeof (response.message) !== 'string' || response.message.length === 0)
          return;

        Notify.create({
          message: response.message,
          icon: "info",
          color: "negative",
          actions: [
            { icon: "close", color: "white", round: true, dense: true },
          ],
        });
      },
    }
  );
}

export function handleDelete(data) {
  const { message, url, fetchItemsCallback, loading } = data;
  Dialog.create({
    title: "Konfirmasi",
    icon: "question",
    message: message,
    focus: "cancel",
    cancel: true,
    persistent: true,
  }).onOk(() => {
    loading.value = true;
    axios
      .post(url)
      .then((response) => {
        Notify.create(response.data.message);
        fetchItemsCallback();
      })
      .finally(() => {
        loading.value = false;
      })
      .catch((error) => {
        let message = "";
        if (error.response.data && error.response.data.message) {
          message = error.response.data.message;
        } else if (error.message) {
          message = error.message;
        }

        if (message.length > 0) {
          Notify.create({ message: message, color: "red" });
        }
        console.log(error);
      });
  });
}

export function handleFetchItems(options) {
  const { pagination, props, rows, url, loading, filter } = options;

  let params = {
    page: pagination.value.page,
    per_page: pagination.value.rowsPerPage,
    order_by: pagination.value.sortBy,
    order_type: pagination.value.descending ? "desc" : "asc",
    filter: filter,
  };

  if (props != null) {
    params.page = props.pagination.page;
    params.per_page = props.pagination.rowsPerPage;
    params.order_by = props.pagination.sortBy;
    params.order_type = props.pagination.descending ? "desc" : "asc";
  }

  loading.value = true;

  axios
    .get(url, { params: params })
    .then((response) => {
      rows.value = response.data.data;
      pagination.value.page = response.data.current_page;
      pagination.value.rowsPerPage = response.data.per_page;
      pagination.value.rowsNumber = response.data.total;
      if (props) {
        pagination.value.sortBy = props.pagination.sortBy;
        pagination.value.descending = props.pagination.descending;
      }
    })
    .finally(() => {
      loading.value = false;
    });
}
