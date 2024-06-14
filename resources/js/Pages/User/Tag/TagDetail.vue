<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, watch, h, reactive, toRaw , createVNode} from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Tabs, TabPane, Card, CardGrid, Button, Space, Modal, message } from 'ant-design-vue';
import { IdcardFilled, PlusSquareOutlined, SearchOutlined,
        FilterOutlined, StarFilled, SoundFilled, RiseOutlined,
        CheckOutlined, EditFilled, ExclamationCircleOutlined } from '@ant-design/icons-vue';

const { props } = usePage();
const tags = ref(props.tags);
const cards = ref(props.cards);
const examples = ref(props.examples);
const notLearn = ref(props.notLearn);
const learning = ref(props.learning);
const learned = ref(props.learned);

const formRef = ref();
const newVocab = ref('');
const newMeaning = ref('');
const newExample = ref('');
const newExampleMeaning = ref('');

const panes = ref([
  {
    title: 'Tab 3',
    content: 'Content of Tab 3',
    id: '0',
    key: '0',
    closable: false,
  },
  ...tags.value.filter(tag => tag.status === 1).map((tag, index) => ({
    id: `${tag.id}`,
    title: `${tag.title}`,
    content: `${tag.description}`,
    key: `${index + 1}`,
    closable: true,
  }))
]);

watch(tags, (newTags) => {
  panes.value = [
    {
      title: 'Tổng quan',
      content: 'Content of Tab 3',
      id: '0',
      key: '0',
      closable: false,
    },
    ...newTags.filter(tag => tag.status === 1).map((tag, index) => ({
      id: `${tag.id}`,
      title: `${tag.title}`,
      content: `${tag.description}`,
      key: `${index + 1}`,
      closable: true,
    }))
  ];
}, { immediate: true });

const activeKey = ref(props.id);
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

const open = ref(false);
const showModal = () => {
  open.value = true;
};

const formState = reactive({
    tagid: activeKey,
    newVocab: newVocab,
    newMeaning: newMeaning,
    newExample: newExample,
    newExampleMeaning: newExampleMeaning
  });

const handleOk = () => {
    Inertia.post(route('vocabulary.add'), toRaw(formState));
    message.success('Thêm từ vựng thành công');
    open.value = false;
};

const showModal2 = ref(false);
const noteTitle = ref((panes.value[activeKey.value]).title);
const noteContent = ref((panes.value[activeKey.value]).content);

function handleOk2() {
    console.log(noteTitle.value);
    Inertia.post(route('tags.update'), {title: noteTitle.value, description: noteContent.value, id: activeKey.value});
    message.success('Đã cập nhật ghi chú');
    showModal2.value = false;
  }
  const showModal22 = () => {
    showModal2.value = true;
  };

const showConfirm = () => {
  Modal.confirm({
    title: 'Bạn vẫn muốn xoá ghi chú này và toàn bộ nội dung trong đó?',
    icon: createVNode(ExclamationCircleOutlined),
    content: h('div', {style: 'color:red;'}, 'Khi bạn click OK, ghi chú này sẽ bị xoá và không thể hoàn tác. Hãy cân nhắc thật kỹ trước khi click!!', ),
    onOk() {
        console.log(tags);
        Inertia.post(route('tags.destroy'), {id: (panes.value[activeKey.value]).id});
        message.success('Đã xoá ghi chú')
      return new Promise((resolve, reject) => {
        setTimeout(Math.random() > 0.5 ? resolve : reject, 1000);
      }).catch(() => console.log('Oops errors!'));
    },
    onCancel() {},
  });
};

</script>

<template>
    <Head title="Ghi chú" />

    <AuthenticatedLayout >
        <Tabs @tabClick="handleTabClick" @change="handleTabClick" v-model:activeKey="activeKey" type="card" @edit="onEdit">
          <TabPane v-for="pane in panes" :id="pane.id" :key="pane.key" :tab="pane.title" :closable="pane.closable">
                <Card title="Mô tả">
                    <CardGrid class="card-grid-left" style="width: 70%;" :hoverable="false">
                        <p>{{ pane.content }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <Space align="start" style="display: flex; align-items: center;">
                                <RiseOutlined style="color: red; font-size: 24px;"></RiseOutlined>
                                <span>
                                    <span>Đang học </span>{{ learning }}<span> từ</span>
                                </span>
                            </Space>
                            <Space align="end" style="display: flex; align-items: center;">
                                <CheckOutlined style="color: green; font-size: 24px;"></CheckOutlined>
                                <span>
                                    <span>Đã học </span>{{ learned }}<span> từ</span>
                                </span>
                            </Space>
                        </div>
                    </CardGrid>
                    <CardGrid style="width: 30%; text-align: center" :hoverable="false">
                        <Space>
                            <Button size="large" style="font-size: 18px; display: flex; align-items: center;" type="primary" :href="route('flashcard.index')" :icon="h(IdcardFilled)">FlashCard</Button>
                            <Button @click="showModal" size="large" style="font-size: 18px; display: flex; align-items: center;" type="primary" :icon="h(PlusSquareOutlined)">Thêm từ</Button>
                            <a-modal v-model:open="open" width="1000px" title="Thêm từ vựng" @ok="handleOk">
                                <a-form :model="formState" :ref="formRef">
                                  <a-form-item label="Tù vựng">
                                    <a-input v-model:value="newVocab" />
                                  </a-form-item>
                                  <a-form-item label="Ý nghĩa">
                                    <a-input v-model:value="newMeaning" />
                                  </a-form-item>
                                  <a-form-item label="Ví dụ thực tế (ENGLISH)">
                                    <a-textarea v-model:value="newExample" rows="4" />
                                  </a-form-item>
                                  <a-form-item label="Ví dụ thực tế (VietNamese)">
                                    <a-textarea v-model:value="newExampleMeaning" rows="4" />
                                  </a-form-item>
                                </a-form>
                            </a-modal>
                        </Space>
                        <Space style="padding-top: 10px;">
                          <Button @click="showModal22" size="large" style="font-size: 18px;" type="primary" :icon="h(EditFilled)">Sửa ghi chú</Button>
                          <a-modal v-model:open="showModal2" title="Chỉnh sửa ghi chú" @ok="handleOk2">
                              <a-form>
                                <a-form-item label="Tiêu đề">
                                  <a-input v-model:value="noteTitle" />
                                </a-form-item>
                                <a-form-item label="Mô tả ghi chú">
                                  <a-textarea v-model:value="noteContent" rows="4" />
                                </a-form-item>
                              </a-form>
                          </a-modal>
                          <Button @click="showConfirm" size="large" style="font-size: 18px; background-color: red; color: aliceblue;">Xoá ghi chú</Button>
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
                        <Card class="custom-shadow" v-for="example in examples" :key="example.id">
                            <CardGrid style="width: 10%; text-align: left" :hoverable="false">
                                {{ example[0].name }}
                            </CardGrid>
                            <CardGrid style="width: 80%; text-align: left" :hoverable="false">
                                {{ example[0].sentence }}
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
          </TabPane>
        </Tabs>
    </AuthenticatedLayout>
</template>

<style scoped>
.right-align {
  display: flex;
  justify-content: flex-end;
}
.custom-shadow {
  box-shadow: 0 2px 5px 2px rgba(0, 0, 0, 0.1);
}
.text-align-right {
    text-align: right;
}
.card-grid-left {
    text-align: left;
    font-size: 24px;
}
</style>
