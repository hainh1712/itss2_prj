<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, watch, h, reactive, toRaw } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { Button, Space, Card, Carousel, Divider, CardGrid } from 'ant-design-vue';
import {CreditCardFilled, SnippetsFilled, PieChartFilled,
        BulbOutlined, SoundOutlined, CaretLeftFilled,
        CaretRightFilled, CloseCircleFilled, CheckCircleFilled,
        StarFilled, SoundFilled, SearchOutlined,FilterOutlined} from '@ant-design/icons-vue';

const { props } = usePage();
const randomCards = ref(props.randomCards);
console.log(randomCards);
const isToggle = ref(false)

function flipCard() {
  isToggle.value = !isToggle.value
}

function speak (text) {
  const utterance = new SpeechSynthesisUtterance(text);
  speechSynthesis.speak(utterance);
};

function star(cardId) {
    Inertia.post(route('card.update'), {cardId: cardId});
}

</script>

<template>
    <Head title="FlashCard" />

    <AuthenticatedLayout >

        <div>
            <Space wrap style="display: flex;">
              <Button type="primary">
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
              <Button :href="route('flashcard.test')">
                <PieChartFilled />
                <span>
                    Kiểm tra
                </span>
              </Button>
            </Space>
        </div>
        <br /><br /><br /><br /><br /><br /><br />

            <div class="card-container">
                <Carousel arrows style="height: 100%;">
                  <template #prevArrow>
                    <div class="custom-slick-arrow" style="left: 10px; z-index: 1">
                      <CaretLeftFilled />
                    </div>
                  </template>
                  <template #nextArrow>
                    <div class="custom-slick-arrow" style="right: 10px">
                      <CaretRightFilled />
                    </div>
                  </template>

                  <div class="card-content" v-for="randomCard in randomCards" :key="randomCard.id" @click="flipCard">
                        <Card v-show="!isToggle" class="custom-shadow">
                            <div class="top-left">
                                <BulbOutlined style="font-size: 24px;"/>
                                <span>
                                    Hiển thị gợi ý
                                </span>
                            </div>
                            <div class="top-right">
                                <SoundOutlined style="font-size: 24px;"/>
                            </div>
                            <div class="center-container">
                                <p class="vocabulary-text">{{ randomCard.name }}</p>
                            </div>
                        </Card>
                        <Card v-show="isToggle" class="custom-shadow">
                            <div class="top-left">
                                <BulbOutlined style="font-size: 24px; color: yellow;"/>
                                <span>
                                    Hiển thị gợi ý
                                </span>
                            </div>
                            <div class="top-right">
                                <SoundOutlined style="font-size: 24px;"/>
                            </div>
                            <div class="center-container">
                                <div class="meaning-section">
                                    <p class="meaning-text">{{ randomCard.meaning }}</p>
                                </div>
                                <div class="example-section">
                                    <p class="example-text">VD: {{ randomCard.sentence }}</p>
                                </div>
                            </div>
                        </Card>
                    </div>
                </Carousel>
            </div>
        <br /><br />
        <div class="check-icon-center">
            <div class="check-icon">
                <div style="width: 60%;">
                    <Space style="display: flex; justify-content: space-between; width: 100%;" direction="horizontal">
                        <a>
                            <CloseCircleFilled style="font-size: 36px; color: red;" />
                        </a>
                        <a>
                            <CheckCircleFilled style="font-size: 36px; color: green;" />
                        </a>
                    </Space>
                </div>
            </div>
        </div>
        <Divider style="height: 1px; background-color: #665A5A" />
        <div class="center-container2" style="width: 100%;">
            <div style="width: 100%;">
                <Card title="Các từ vựng" style="font-size: 18px;">
                    <div class="right-align">
                      <Space>
                        <Button style="background-color: #d9d9d9; display: flex; align-items: center;" :icon="h(SearchOutlined)">Tìm kiếm từ</Button>
                        <Button style="background-color: #d9d9d9; align-items: center;" :icon="h(FilterOutlined)"></Button>
                      </Space>
                    </div>
                    <br/>
                    <div style="padding-bottom: 16px;">
                        <Card class="custom-shadow2" v-for="randomCard in randomCards" :key="randomCard.id">
                            <CardGrid style="width: 10%; text-align: left" :hoverable="false">
                                {{ randomCard.name }}
                            </CardGrid>
                            <CardGrid style="width: 80%; text-align: left" :hoverable="false">
                                {{ randomCard.sentence }}
                            </CardGrid>
                            <CardGrid style="width: 10%; text-align: center" :hoverable="false">
                                <Space>
                                    <a>
                                        <StarFilled @click="star(randomCard.id)" :style="{color: randomCard.is_favorite === 1 ? 'yellow' : 'gray'}" style="font-size: 24px;"></StarFilled>
                                    </a>
                                    <a>
                                        <SoundFilled @click="speak(randomCard.name)" style="font-size: 24px; color: gray;"></SoundFilled>
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


</style>
