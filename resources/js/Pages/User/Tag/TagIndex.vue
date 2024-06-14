<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, watch, h } from 'vue';
import { Tabs, TabPane, Card, CardGrid, Button, Space, message } from 'ant-design-vue';
import { Inertia } from '@inertiajs/inertia';
import { IdcardFilled, PlusSquareOutlined, SearchOutlined, FilterOutlined, StarFilled, SoundFilled, RiseOutlined, CheckOutlined } from '@ant-design/icons-vue';

const { props } = usePage();
const tags = ref(props.tags);
// const data = ref(props.data);
// const a = data.value;
// console.log(a[1].examples);
var b = [];

// for(var i = 0; i < a.length; i++){
//     b[i] = a[0].examples[i];
//     console.log(b[i][0].sentence);
//     console.log(b[i][0].name);
//     console.log(b[i][0].meaning);
//     console.log(b[i][0].ex_meaning);
// }

const panes = ref([
  {
    title: 'Tab 3',
    content: 'Content of Tab 3',
    key: '0',
    closable: false,
  },
  ...tags.value.filter(tag => tag.status === 1).map((tag, index) => ({
    id: `${tag.id}`,
    title: `${tag.title}`,
    content: `${tag.description}`,
    key: `${index + 1}`,
    closable: true,
    // examples: a[index].examples,
    // learning: a[index].learning,
    // learned: a[index].learned
  }))
]);

console.log(panes.value);

watch(tags, (newTags) => {
  panes.value = [
    {
      title: 'Tổng quan',
      content: 'Content tổng quan chưa có',
      key: '0',
      closable: false,
    },
    ...newTags.filter(tag => tag.status === 1).map((tag, index) => ({
      id: `${tag.id}`,
      title: `${tag.title}`,
      content: `${tag.description}`,
      key: `${index + 1}`,
      closable: true,
    //   examples: a[index].examples,
    //   learning: a[index].learning,
    //   learned: a[index].learned
    }))
  ];
}, { immediate: true });

const activeKey = ref(panes.value.length ? panes.value[0].key : '0');
const newTabIndex = ref(panes.value.length);

const add = () => {
  const newKey = `newTab${++newTabIndex.value}`;
  activeKey.value = newKey;
  panes.value.push({
    title: 'New Tab',
    content: 'Content of new Tab',
    key: newKey,
    closable: true,
  });
};

const remove = (targetKey) => {
  let lastIndex = 0;
  panes.value.forEach((pane, i) => {
    if (pane.key === targetKey) {
      lastIndex = i - 1;
    }
  });
  panes.value = panes.value.filter(pane => pane.key !== targetKey);
  if (panes.value.length && activeKey.value === targetKey) {
    if (lastIndex >= 0) {
      activeKey.value = panes.value[lastIndex].key;
    } else {
      activeKey.value = panes.value[0].key;
    }
  }
};

const onEdit = (targetKey, action) => {
  if (action === 'add') {
    add();
  } else {
    remove(targetKey);
  }
};

function handleTabClick(id, event) {
  if(id === '0'){
    Inertia.visit(`/tags`);
  }else{
    Inertia.visit(`/tags/${id}`);
  }
}

const showModal2 = ref(false);
const noteTitle = ref('');
const noteContent = ref('');

function handleOk2() {
    console.log(noteTitle.value);
    Inertia.post(route('tags.store'), {title: noteTitle.value, description: noteContent.value});
    message.success('Tạo ghi chú mới thành công')
    showModal2.value = false;
  }
  const showModal = () => {
    showModal2.value = true;
  };
</script>

<template>
    <Head title="Ghi chú" />

    <AuthenticatedLayout >
        <Tabs @tabClick="handleTabClick" v-model:activeKey="activeKey" type="card" @edit="onEdit">
          <TabPane v-for="pane in panes" :id="pane.id" :key="pane.key" :tab="pane.title" :closable="pane.closable">
            <div v-if="pane.key === '0'">
              <!-- Card Tổng quan -->
              <Card title="Tổng quan">
                  <CardGrid style="width: 80%; text-align: left" :hoverable="false">
                    {{ pane.content }}
                  </CardGrid>
                  <CardGrid style="width: 20%; text-align: center" :hoverable="false">
                      <Space>
                          <Button @click="showModal" size="large" style="font-size: 18px;" type="primary" :icon="h(PlusSquareOutlined)">Tạo ghi chú mới </Button>
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
                      </Space>
                  </CardGrid>
              </Card>
            </div>
            <div v-else>
                <Card title="Mô tả">
                    <CardGrid class="card-grid-left" style="width: 75%;" :hoverable="false">
                        <p>{{ pane.content }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <Space align="start" style="display: flex; align-items: center;">
                                <RiseOutlined style="color: red; font-size: 24px;"></RiseOutlined>
                                <span>
                                    <span>Đang học </span>{{ `${pane.learning}` }}<span> từ</span>
                                </span>
                            </Space>
                            <Space align="end" style="display: flex; align-items: center;">
                                <CheckOutlined style="color: green; font-size: 24px;"></CheckOutlined>
                                <span>
                                    <span>Đã học </span>{{ `${pane.learned}` }}<span> từ</span>
                                </span>
                            </Space>
                        </div>
                    </CardGrid>
                    <CardGrid style="width: 25%; text-align: center" :hoverable="false">
                        <Space>
                            <Button size="large" style="font-size: 18px; display: flex; align-items: center;" type="primary" :icon="h(IdcardFilled)">FlashCard</Button>
                            <Button size="large" style="font-size: 18px; display: flex; align-items: center;" type="primary" :icon="h(PlusSquareOutlined)">Thêm từ</Button>
                        </Space>

                    </CardGrid>
                </Card>
                <Card title="Các từ vựng" style="font-size: 18px;">
                    <div class="right-align">
                      <Space>
                        <Button style="background-color: #d9d9d9; display: flex; align-items: center;" :icon="h(SearchOutlined)">Tìm kiếm từ</Button>
                        <Button style="background-color: #d9d9d9; align-items: center;" :icon="h(FilterOutlined)"></Button>
                      </Space>
                    </div>
                    <br/>
                    <div>
                        <Card class="custom-shadow" v-for="example in p.examples[p.key]" :key="example.id" style="margin-bottom: 20px;">
                            <CardGrid style="width: 10%; text-align: left" :hoverable="false">
                                <p>{{ example[0].name }}</p>
                                <p>{{ example[0].meaning }}</p>
                            </CardGrid>
                            <CardGrid style="width: 80%; text-align: left" :hoverable="false">
                                <p>{{ example[0].sentence }}</p>
                                <p>{{ example[0].ex_meaning }}</p>
                            </CardGrid>
                            <CardGrid style="width: 10%; text-align: center" :hoverable="false">
                                <Space>
                                    <a>
                                        <StarFilled :style="{color: example[0].is_favorite === 1 ? 'yellow' : 'black'}" style="font-size: 24px;"></StarFilled>
                                    </a>
                                    <a>
                                        <SoundFilled style="font-size: 24px;"></SoundFilled>
                                    </a>
                                </Space>
                            </CardGrid>
                        </Card>
                        <br />
                    </div>
                </Card>
            </div>
          </TabPane>
        </Tabs>
    </AuthenticatedLayout>
</template>

<style scoped>
.right-align {
  display: flex;
  justify-content: flex-end;
}
</style>
