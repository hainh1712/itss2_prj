<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import { ref, watch, h, reactive, toRaw } from 'vue';
import { Button, Space, Card, Carousel, Divider } from 'ant-design-vue';
import {CreditCardFilled, SnippetsFilled, PieChartFilled,
        BulbOutlined, SoundOutlined, CaretLeftFilled,
        CaretRightFilled, CloseCircleFilled, CheckCircleFilled,
        StarFilled, SoundFilled, SearchOutlined,FilterOutlined, FullscreenOutlined} from '@ant-design/icons-vue';


const { props } = usePage();
const randomCards = ref(props.randomCards);
console.log(randomCards);
const isToggle = ref(false)

function flipCard() {
  isToggle.value = !isToggle.value
}

const fullScreenElement = ref(null);

const goFullScreen = () => {
  if (fullScreenElement.value.requestFullscreen) {
    fullScreenElement.value.requestFullscreen();
  } else if (fullScreenElement.value.webkitRequestFullscreen) {
    fullScreenElement.value.webkitRequestFullscreen();
  } else if (fullScreenElement.value.msRequestFullscreen) {
    fullScreenElement.value.msRequestFullscreen();
  }
};

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
              <Button type="primary">
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

            <div v-bind:class="fullScreenElement == null ? 'flashcard2' : 'y'" class="card-container "  ref="fullScreenElement">
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
            <div class="card-container" style="display: flex; justify-content: right; padding-top: 10px; padding-right: 10px;">
                <button  @click="goFullScreen">
                    <FullscreenOutlined style="font-size: 24px;" />
                </button>
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

.flashcard2 {
    height: 100%;
    width: 100%;
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
