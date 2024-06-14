<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { useTimer, useStopwatch } from "vue-timer-hook";
import { ref, watch, h, reactive, toRaw, computed, onMounted, watchEffect, defineComponent } from 'vue';
import { Button, Space, Card, Carousel, Divider, CardGrid, Alert, Modal } from 'ant-design-vue';
import {CreditCardFilled, SnippetsFilled, PieChartFilled,
        BulbOutlined, SoundOutlined, CaretLeftFilled,
        CaretRightFilled, CloseCircleFilled, CheckCircleFilled,
        StarFilled, SoundFilled, SearchOutlined,FilterOutlined,
        FullscreenOutlined, DiffFilled, ClockCircleFilled} from '@ant-design/icons-vue';


const { props } = usePage();

const historyTests = ref(props.tests);
const cardAll = ref(props.cardAll);
const cardAllValue = cardAll.value;
const tests = ref(props.tests);

const info = () => {
    if((tests.value).length > 0){
        const messages = tests.value.map((test, index) => h('p', `Lần ${index + 1}: ${test.score}`));
        Modal.info({
          title: 'Lịch sử điểm',
          content: h('div', {}, messages),
          onOk() {
            console.log('ok');
          },
        });
    }else{
        const message = h('p', { style: 'color: red;'}, 'Bạn chưa làm bài kiểm tra nào');
        Modal.info({
          title: 'Lịch sử điểm',
          content: h('div', {}, message),
          onOk() {
            console.log('ok');
          },
        });
    }
};

const getRandomValues = (arr, num) => {
  const shuffled = [...arr].sort(() => 0.5 - Math.random());
  return shuffled.slice(0, num);
};

const value = ref('');

const correctAnswers = ref([]);
const resultModalVisible = ref(false);
const results = ref([]);

const value1 = ref(10);
const focus = () => {
  console.log('focus');
};
const handleChange = value => {
  console.log(`selected ${value}`);
};

const value2 = ref(10);
const focus2 = () => {
  console.log('focus');
};
const handleChange2 = value2 => {
  console.log(`selected ${value2}`);
};

const open = ref(false);
const showModal = () => {
  open.value = true;
};
const handleOk = e => {
  console.log(e);
  open.value = false;
};

const open2 = ref(false);
const open3 = ref(false);

const showModal2 = () => {
  open2.value = true;
};
const showModal3 = () => {
  open3.value = true;
};

const randomCardAllValues = ref([])
const answers = ref({});

const getRandomAnswers = (correctAnswer, allAnswers) => {
  // Lấy 3 đáp án nhiễu ngẫu nhiên
  const distractors = allAnswers
    .filter(answer => answer !== correctAnswer)
    .sort(() => 0.5 - Math.random())
    .slice(0, 3);

  // Thêm đáp án đúng vào danh sách
  const answerOptions = [...distractors, correctAnswer];

  // Trộn các đáp án ngẫu nhiên
  return answerOptions.sort(() => 0.5 - Math.random());
};

onMounted(() => {
  randomCardAllValues.value = cardAll.value.map(card => {
    const answerOptions = getRandomAnswers(card.meaning, cardAll.value.map(card => card.meaning));
    return {
      ...card,
      options: answerOptions,
    };
  });
});

const handlePick = (value, index) => {
  answers.value[index] = value;
};

const handleOk2 = a => {
    if(value1.value >= cardAllValue.length){
    randomCardAllValues.value = cardAllValue.map(card => {
      const answerOptions = getRandomAnswers(card.meaning, cardAllValue.map(card => card.meaning));
      return {
        ...card,
        options: answerOptions,
      };
    });
  } else {
    const selectedCards = getRandomValues(cardAllValue, value1.value);
    randomCardAllValues.value = selectedCards.map(card => {
      const answerOptions = getRandomAnswers(card.meaning, cardAllValue.map(card => card.meaning));
      return {
        ...card,
        options: answerOptions,
      };
    });
  }
  correctAnswers.value = randomCardAllValues.value.map(card => card.meaning);
  showModal3();
  open2.value = false;
};

const correctCount = ref(0)

const handleOk3 = e => {
  results.value = randomCardAllValues.value.map((card, index) => {
    const isCorrect = answers.value[index] === card.meaning;
    if (isCorrect) (correctCount.value)++;
    return {
      question: card.name,
      selectedAnswer: answers.value[index],
      correctAnswer: card.meaning,
      isCorrect: isCorrect,
    };
  });

  open3.value = false;
  resultModalVisible.value = true;
};

function speak (text) {
  const utterance = new SpeechSynthesisUtterance(text);
  speechSynthesis.speak(utterance);
};

const reload = e => {
    resultModalVisible.value = false;
    Inertia.post(route('test.add'), {score: (correctCount.value)*10});
}

function star(cardId) {
    Inertia.post(route('card.update'), {cardId: cardId});
}

</script>

<template>
    <Head title="Học FlashCard"/>
    <AuthenticatedLayout>
        <div>
            <Space wrap style="display: flex;">
              <Button :href="route('flashcard.index')">
                <CreditCardFilled />
                <span>
                    Thẻ ghi nhớ
                </span>
              </Button>
              <Button :href="route('flashcard.learn')">
                <SnippetsFilled />
                <span>
                    Học
                </span>
              </Button>
              <Button type="primary">
                <PieChartFilled />
                <span>
                    Kiểm tra
                </span>
              </Button>
            </Space>
        </div>
        <br /><br /><br />
        <div>
            <Button @click="info" style="background-color:blueviolet;">
                <ClockCircleFilled style="color:blue;" />
                <span style="color: aliceblue;">
                    Lịch sử điểm
                </span>

            </Button>
            <Button @click="showModal2" style="background-color: chocolate;">
                <DiffFilled style="color:blue;"/>
                <span style="color: aliceblue;">
                    Tạo bài kiểm tra
                </span>
                <a-modal  v-model:open="open2" title="Cấu trúc bài kiểm tra" @ok="handleOk2">
                    <div>
                        <p>Số câu</p>
                        <a-select
                          ref="select"
                          v-model:value="value1"
                          style="width: 120px"
                          @focus="focus"
                          @change="handleChange" >
                          <a-select-option value=10>10</a-select-option>
                          <a-select-option value=15>15</a-select-option>
                          <a-select-option value=20>20</a-select-option>
                        </a-select>
                    </div>
                    <div>
                        <p style="padding-top: 10px;">Thời gian</p>
                        <a-select
                          ref="select2"
                          v-model:value="value2"
                          style="width: 120px"
                          @focus="focus2"
                          @change="handleChange2">
                          <a-select-option value=10>10</a-select-option>
                          <a-select-option value=15>15</a-select-option>
                          <a-select-option value=20>20</a-select-option>
                          <a-select-option value=25>25</a-select-option>
                          <a-select-option value=30>30</a-select-option>
                          <a-select-option value=35>35</a-select-option>
                          <a-select-option value=40>40</a-select-option>
                          <a-select-option value=45>45</a-select-option>
                          <a-select-option value=50>50</a-select-option>
                          <a-select-option value=55>55</a-select-option>
                          <a-select-option value=60>60</a-select-option>
                        </a-select>
                        <span style="padding-left: 10px;">Phút</span>
                    </div>
                </a-modal>
                <div>
                  <a-modal
                    v-model:open="open3"
                    title="Bài kiểm tra"
                    width="100%"
                    height="100%"
                    okText = "Nộp bài"
                    cancelText = "Huỷ bài"
                    wrap-class-name="full-modal"
                    @ok= "handleOk3">
                    <div v-for="(randomCardAllValue, index) in randomCardAllValues" :key="index">
                        <p>Câu hỏi {{ index + 1 }}: Ý nghĩa của {{ randomCardAllValue.name }} là:</p>
                        <a-radio-group v-model:value="answers[index]" @change="value => handleChange(value, index)" name="radioGroup">
                            <a-radio v-for="(option, idx) in randomCardAllValue.options" :key="idx" :value="option">{{ option }}</a-radio>
                        </a-radio-group>
                    </div>
                  </a-modal>
                  <a-modal v-model:visible="resultModalVisible" title="Kết quả bài kiểm tra" @ok="reload">
                      <div v-for="(result, index) in results" :key="index">
                        <Alert
                          :message="'Câu hỏi ' + (index + 1) + ': ' + result.question"
                          :description="'Bạn đã chọn: ' + result.selectedAnswer + ' | Đáp án đúng: ' + result.correctAnswer"
                          :type="result.isCorrect ? 'success' : 'error'"
                          show-icon
                        />
                        <br/>
                      </div>
                      <div>
                        <p>Điểm của bạn là {{ correctCount * 10 }}</p>
                      </div>
                    </a-modal>
                </div>
            </Button>
        </div>
        <br /><br /><br /><br />
        <div class="center-container2" style="width: 100%;">
            <div style="width: 100%;">
                <Card title="Các từ vựng đã học" style="font-size: 18px;">
                    <div class="right-align">
                      <Space>
                        <Button style="background-color: #d9d9d9; display: flex; align-items: center;" :icon="h(SearchOutlined)">Tìm kiếm từ</Button>
                        <Button style="background-color: #d9d9d9; align-items: center;" :icon="h(FilterOutlined)"></Button>
                      </Space>
                    </div>
                    <br/>
                    <div style="padding-bottom: 16px;">
                        <Card class="custom-shadow2" v-for="card in cardAll" :key="card.id">
                            <CardGrid style="width: 10%; text-align: left" :hoverable="false">
                                {{ card.name }}
                            </CardGrid>
                            <CardGrid style="width: 80%; text-align: left" :hoverable="false">
                                {{ card.sentence }}
                            </CardGrid>
                            <CardGrid style="width: 10%; text-align: center" :hoverable="false">
                                <Space>
                                    <a>
                                        <StarFilled @click="star(card.id)" :style="{color: card.is_favorite === 1 ? 'yellow' : 'gray'}" style="font-size: 24px;"></StarFilled>
                                    </a>
                                    <a>
                                        <SoundFilled @click="speak(card.name)" style="font-size: 24px; color: gray;"></SoundFilled>
                                    </a>
                                </Space>
                            </CardGrid>
                        </Card>
                        <br />
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.center-container {
  display: flex;
  flex-direction: column;  /* Sắp xếp các phần tử theo chiều dọc */
  justify-content: center; /* Căn giữa theo chiều dọc */
  align-items: center;    /* Căn giữa theo chiều ngang */
  text-align: center;      /* Căn giữa text */
  height: 100%;           /* Chiếm toàn bộ chiều cao của thẻ cha */
}

.center-container2 {
  display: flex;
  justify-content: center;
  align-items: center;
}

.right-align {
    display: flex;
    align-items: right;
}

.check-icon-center {
  display: flex;
  justify-content: center;
  margin-top: 50px;
}

.custom-shadow {
  box-shadow: 0 5px 10px 5px rgba(255, 255, 255, 0.1);
  width: 100%;
  min-height: 400px;  /* Đặt chiều cao tối thiểu */
  padding: 20px;
  display: flex;        /* Kích hoạt Flexbox */
  flex-direction: column; /* Sắp xếp theo chiều dọc */
  justify-content: center; /* Căn giữa theo chiều dọc */
  align-items: center;    /* Căn giữa theo chiều ngang */
  position: relative;     /* Để sử dụng absolute positioning cho các phần tử con */
  border-radius: 30px;
  background-color: #dadada;
}

.custom-shadow2 {
  box-shadow: 0 5px 10px 5px rgba(0, 0, 0, 0.1);
}

.top-left {
  position: absolute;
  top: 20px;
  left: 20px;
  display: flex;
  align-items: center;
}

.top-right {
  position: absolute;
  top: 20px;
  right: 20px;
  display: flex;
  align-items: center;
}

.flashcard {
  border: 1px solid black;
  cursor: pointer;

}

.card-container {
  width: 60%;
  margin: 0 auto; /* Giữ khung flashcard ở giữa */
}

.card-content {
  display: flex;
  flex-direction: column; /* Stack content vertically */
  align-items: center;   /* Center items horizontally */
  justify-content: center; /* Center items vertically */
  height: 100%;
}

.vocabulary-text {
  font-size: 40px;
  margin-bottom: 10px; /* Add space between vocabulary and example */
}

.meaning-section {
  margin-bottom: 20px; /* Add space between example and meaning */
}

.example-section {
  margin-top: 20px; /* Add space between meaning and example */
}

.meaning-text, .example-text {
  margin: 0; /* Remove default margins */
  font-size:30px;
}

:deep(.slick-arrow.custom-slick-arrow) {
  font-size: 25px;
  color: black;
  background-color: #fff;
  transition: ease all 0.3s;
  opacity: 0.3;
  z-index: 1;
}

:deep(.slick-arrow.custom-slick-arrow:before) {
  display: none;
}

.full-modal {
  .ant-modal {
    max-width: 100%;
    max-height: 100%;
    top: 0;
    padding-bottom: 0;
    margin: 0;
  }
  .ant-modal-content {
    display: flex;
    flex-direction: column;
    height: calc(100vh);
  }
  .ant-modal-body {
    flex: 1;
  }
}

</style>
