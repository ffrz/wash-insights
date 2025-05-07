<script setup>
import { formatNumber } from "@/helpers/utils";
import { router, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();
const title = `Rincian Produk #${page.props.data.id}`;
const tab = ref("main");
</script>

<template>
  <i-head :title="title" />
  <authenticated-layout>
    <template #title>{{ title }}</template>
    <div class="row justify-center">
      <div class="col col-lg-6 q-pa-sm">
        <div class="row">
          <q-card square flat bordered class="q-card col">
            <q-tabs v-model="tab" align="left">
              <q-tab name="main" label="Info Utama" />
              <q-tab name="history" label="Riwayat Stok" />
            </q-tabs>
            <q-tab-panels v-model="tab">
              <q-tab-panel name="main">
                <div class="text-subtitle1 text-bold text-grey-9">
                  Info Produk
                </div>
                <table class="detail">
                  <tbody>
                    <tr>
                      <td style="width:120px">ID</td>
                      <td style="width:1px">:</td>
                      <td># {{ page.props.data.id }}</td>
                    </tr>
                    <tr>
                      <td>Nama Produk</td>
                      <td>:</td>
                      <td>
                        {{ page.props.data.name }}
                      </td>
                    </tr>
                    <tr>
                      <td>Jenis Produk</td>
                      <td>:</td>
                      <td>
                        {{ $CONSTANTS.PRODUCT_TYPES[page.props.data.type] }}
                      </td>
                    </tr>
                    <tr>
                      <td>Kategori</td>
                      <td>:</td>
                      <td>
                        {{ page.props.data.category ? page.props.data.category.name : '--Tidak memiliki kategori--' }}
                      </td>
                    </tr>
                    <tr>
                      <td>Supplier</td>
                      <td>:</td>
                      <td>
                        <template v-if="page.props.data.supplier">
                          <i-link :href="route('admin.supplier.detail', { id: page.props.data.supplier.id })">
                            {{ '#' + page.props.data.supplier.id + ' - ' +
                              page.props.data.supplier.name }}
                          </i-link>
                        </template>
                        <template v-else>
                          {{ '--Tidak memiliki supplier--' }}
                        </template>
                      </td>
                    </tr>
                    <tr>
                      <td>Status</td>
                      <td>:</td>
                      <td>
                        {{ page.props.data.active ? "Aktif" : "Tidak Aktif" }}
                      </td>
                    </tr>
                    <tr v-if="!!page.props.data.created_datetime">
                      <td>Dibuat Oleh</td>
                      <td>:</td>
                      <td>
                        <template v-if="page.props.data.created_by">
                          <i-link :href="route('admin.user.detail', { id: page.props.data.created_by_uid })">
                            {{ page.props.data.created_by.username }} - {{ page.props.data.created_by.name }}
                          </i-link>
                          -
                        </template>
                        {{ $dayjs(new Date(page.props.data.created_datetime)).format("dddd, D MMMM YYYY pukul HH:mm:ss")
                        }}
                      </td>
                    </tr>
                    <tr v-if="!!page.props.data.updated_datetime">
                      <td>Diperbarui oleh</td>
                      <td>:</td>
                      <td>
                        <template v-if="page.props.data.updated_by">
                          <i-link :href="route('admin.user.detail', { id: page.props.data.updated_by_uid })">
                            {{ page.props.data.updated_by.username }} - {{ page.props.data.updated_by.name }}
                          </i-link>
                          -
                        </template>
                        {{ $dayjs(new Date(page.props.data.updated_datetime)).format("dddd, D MMMM YYYY pukul HH:mm:ss")
                        }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div class="text-subtitle1 q-pt-lg text-bold text-grey-9">
                  Info Inventori
                </div>
                <table class="detail">
                  <tbody>
                    <tr>
                      <td style="width:120px">Stok</td>
                      <td style="width:1px">:</td>
                      <td>
                        {{ formatNumber(page.props.data.stock) }} {{ page.props.data.uom }}
                      </td>
                    </tr>
                    <tr>
                      <td>Stok Minimum</td>
                      <td>:</td>
                      <td>
                        {{ formatNumber(page.props.data.min_stock) }} {{ page.props.data.uom }}
                      </td>
                    </tr>
                    <tr>
                      <td>Stok Maksimum</td>
                      <td>:</td>
                      <td>
                        {{ formatNumber(page.props.data.max_stock) }} {{ page.props.data.uom }}
                      </td>
                    </tr>
                    <tr>
                      <td>Barcode</td>
                      <td>:</td>
                      <td>{{ page.props.data.barcode }}</td>
                    </tr>
                  </tbody>
                </table>
                <div class="text-subtitle1 q-pt-md text-bold text-grey-9">
                  Info Harga
                </div>
                <table class="detail">
                  <tbody>
                    <tr v-if="$page.props.auth.user.role == $CONSTANTS.USER_ROLE_ADMIN">
                      <td style="width:120px">Harga Beli</td>
                      <td style="width:1px">:</td>
                      <td>Rp. {{ formatNumber(page.props.data.cost) }}</td>
                    </tr>
                    <tr>
                      <td>Harga Jual</td>
                      <td>:</td>
                      <td>Rp. {{ formatNumber(page.props.data.price) }}</td>
                    </tr>
                    <tr v-if="$page.props.auth.user.role == $CONSTANTS.USER_ROLE_ADMIN">
                      <td>Margin</td>
                      <td>:</td>
                      <td>{{ page.props.data.price > 0 ?
                        formatNumber(
                          (page.props.data.price - page.props.data.cost) / page.props.data.price * 100,
                          'id-ID', 2
                        ) : 0 }} %</td>
                    </tr>
                  </tbody>
                </table>
                <div class="text-subtitle1 q-pt-md text-bold text-grey-9">
                  Info Deskripsi & Catatan
                </div>
                <table class="detail">
                  <tbody>
                    <tr>
                      <td style="width:120px">Deskirpsi</td>
                      <td style="width:1px">:</td>
                      <td>{{ page.props.data.description }}</td>
                    </tr>
                    <tr>
                      <td>Catatan</td>
                      <td>:</td>
                      <td>{{ page.props.data.notes }}</td>
                    </tr>
                  </tbody>
                </table>
              </q-tab-panel>

              <q-tab-panel name="history">

              </q-tab-panel>
            </q-tab-panels>
          </q-card>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>
