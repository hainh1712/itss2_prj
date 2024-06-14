<template>
  <AuthenticatedLayout>
    <div >
        <p style="font-style: 24px;">Tìm kiếm từ vựng</p>
    </div>
    <div class="search-container">
      <a-input-search
        v-model:value="searchQuery"
        placeholder="Search..."
        enter-button
        @search="handleSearch"
        class="search-bar"
      />
      <div class="results-container" v-if="message">
            <a-button type="primary" class="result-button">
                Kết quả
            </a-button>
            <p>Từ vựng không tồn tại trong thực tế hoặc không tồn tại trong hệ thống.</p>
        </div>
      <div v-if="showResults" class="results-container">
        <a-button type="primary" class="result-button">
          Kết quả
        </a-button>
        <div class="result-content">
          <div class="meaning-section">
            <h3 style="font-weight:bold; font-size:20px">Ý nghĩa</h3>
            <p>{{ meaning }}</p>
          </div>
          <a-button type="primary" class="note-button" @click="showModal = true">
            Thêm ghi chú
          </a-button>
        </div>
        <a-divider />
        <div class="examples-section">
          <h3 style="font-weight:bold; font-size:20px">Các ví dụ thực tế ({{ examples.length }})</h3>
          <div v-for="(example, index) in examples" :key="index" class="example-card">
            <p>{{ example.en }}</p>
            <a-divider />
            <p>{{ example.vn }}</p>
            <div class="example-actions">
              <SoundOutlined />
              <StarOutlined />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal để thêm ghi chú -->
    <a-modal v-model:visible="showModal" title="Thêm ghi chú" @ok="handleOk" @cancel="handleCancel">
      <a-form>
        <a-form-item label="Tiêu đề">
          <a-input v-model:value="noteTitle" />
        </a-form-item>
        <a-form-item label="Nội dung">
          <a-textarea v-model:value="noteContent" rows="4" />
        </a-form-item>
      </a-form>
    </a-modal>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Input, Button, Divider, Modal, Form, FormItem, Input as AntInput, Textarea } from 'ant-design-vue';
import { SoundOutlined, StarOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {onMounted} from 'vue';
import { Inertia } from '@inertiajs/inertia';

const {props} = usePage();

const message = ref(props.flash?.message || null);

console.log(message.value)

const searchQuery = ref('');
const showResults = ref(false);

const showModal = ref(false);
const noteTitle = ref('');
const noteContent = ref('');

function handleSearch() {
  Inertia.visit(`/search/${searchQuery.value}`);
}

function handleOk() {
  console.log('Tiêu đề:', noteTitle.value);
  console.log('Nội dung:', noteContent.value);
  showModal.value = false;
}

function handleCancel() {
  showModal.value = false;
}
</script>

<style scoped>
.search-container {
  padding: 20px;
  border-radius: 30px;
}

.search-bar {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  border-radius: 40px; /* Bo tròn góc */
}

.results-container {
  margin-top: 20px;
}

.result-button {
  margin-bottom: 10px;
}

.result-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.meaning-section {
  flex: 1;
}

.note-button {
  margin-left: 20px;
}

.examples-section {
  margin-top: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Đổ bóng cho toàn bộ phần */
  padding: 20px; /* Thêm khoảng cách bên trong */
  border-radius: 10px; /* Bo tròn góc */
  background: #fff; /* Nền trắng cho phần */
}

.example-card {
  background: #f9f9f9;
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.example-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
</style>
