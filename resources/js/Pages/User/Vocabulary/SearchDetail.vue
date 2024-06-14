<template>
    <AuthenticatedLayout>
      <div class="search-container">
        <a-input-search
          v-model:value="searchQuery"
          placeholder="Search..."
          enter-button
          @search="handleSearch"
          class="search-bar"
        />
        <div v-if="searchQuery === null">
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
            <!-- <a-button type="primary" class="note-button" @click="showModal1 = true">
              Thêm vào ghi chú
            </a-button> -->
          </div>
          <a-divider />
          <div class="examples-section">
            <h3 style="font-weight:bold; font-size:20px">Các ví dụ thực tế ({{ examples.length }})</h3>
            <div v-for="(example, index) in examples" :key="index" class="example-card">
              <p>{{ example.sentence }}</p>
              <a-divider />
              <p>{{ example.meaning }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal để thêm ghi chú -->
      <a-modal v-model:visible="showModal1" title="Thêm vào ghi chú" @ok="handleOk" @cancel="handleCancel">
        <a-form>
          <a-form-item label="Chọn ghi chú">
            <a-select v-model:value="examples" placeholder="Chọn ghi chú bạn muốn thêm vào">
                <a-select-option value="">Zone one</a-select-option>
                <a-select-option value="">Zone two</a-select-option>
            </a-select>
          </a-form-item>
        </a-form>
        <div>
            <a-space>
                <p>Tạo ghi chú mới: </p>
                <a-button type="primary" @click="showModal">Open Modal</a-button>
                <a-modal v-model:open="showModal2" title="Ghi chú mới" @ok="handleOk2">
                    <a-form>
                      <a-form-item label="Tiêu đề">
                        <a-input v-model:value="noteTitle" />
                      </a-form-item>
                      <a-form-item label="Mô tả ghi chú">
                        <a-textarea v-model:value="noteContent" rows="4" />
                      </a-form-item>
                    </a-form>
                </a-modal>
            </a-space>
        </div>
      </a-modal>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { Input, Button, Divider, Modal, Form, FormItem, Input as AntInput, Textarea, Select, SelectOption } from 'ant-design-vue';
  import { SoundOutlined, StarOutlined } from '@ant-design/icons-vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Link, router, usePage } from '@inertiajs/vue3';
  import { Inertia } from '@inertiajs/inertia';

  const { props } = usePage();
  const searchQuery = ref(props.vocab) ?? null;
  console.log(searchQuery);

  const vocabularies = ref(props.vocabulary);
  const showResults = ref(true);
  const vocabulary = vocabularies.value;
  const meaning = vocabulary[0].meaning;
  const examples = ref(props.examples);

  console.log(searchQuery, vocabularies, vocabulary);

  const showModal1 = ref(false);
  const showModal2 = ref(false);
  const noteTitle = ref('');
  const noteContent = ref('');

  function handleSearch() {
    console.log(searchQuery.value);
  Inertia.visit(`/search/${searchQuery.value}`);
  }

  const showModal = () => {
    showModal1.value = false;
    showModal2.value = true;
  };

  function handleOk() {
    console.log('Tiêu đề:', noteTitle.value);
    console.log('Nội dung:', noteContent.value);
    showModal1.value = false;
  }

  function handleOk2() {
    showModal2.value = false;
  }

  function handleCancel() {
    showModal1.value = false;
  }

  function handleCancel2() {
    showModal2.value = false;
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
