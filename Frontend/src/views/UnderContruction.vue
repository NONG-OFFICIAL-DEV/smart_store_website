<template>
  <v-app :theme="theme">
    <!-- ── Mobile drawer ──────────────────────────────────────────────── -->
    <v-navigation-drawer
      v-model="drawer"
      temporary
      location="right"
      width="260"
    >
      <v-list class="pa-3">
        <v-list-item
          v-for="link in navLinks"
          :key="link.href"
          :href="link.href"
          rounded="lg"
          class="mb-1"
          @click="drawer = false"
        >
          <v-list-item-title class="font-weight-bold text-body-1">
            {{ t(link.key) }}
          </v-list-item-title>
        </v-list-item>
        <v-divider class="my-3" />
        <v-list-item>
          <v-btn
            block
            color="primary"
            rounded="xl"
            href="#contact"
            @click="drawer = false"
          >
            {{ t('landing.hero.cta_primary') }}
          </v-btn>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <!-- ── Navbar ─────────────────────────────────────────────────────── -->
    <v-app-bar flat height="68" class="glass-nav px-md-8">
      <div class="logo-text">
        Smart
        <span class="accent-text">Store</span>
      </div>
      <v-spacer />

      <div class="d-none d-md-flex align-center gap-7 mr-8">
        <a
          v-for="link in navLinks"
          :key="link.href"
          :href="link.href"
          class="nav-link"
        >
          {{ t(link.key) }}
        </a>
      </div>

      <div class="d-flex align-center gap-2">
        <!-- Lang -->
        <v-btn
          variant="tonal"
          rounded="pill"
          size="small"
          class="lang-btn"
          min-width="64"
        >
          {{ locale === 'en' ? 'EN' : 'KM' }}
          <v-icon end size="14">mdi-chevron-down</v-icon>
          <v-menu activator="parent" :close-on-content-click="true">
            <v-list
              density="compact"
              rounded="xl"
              elevation="4"
              min-width="130"
            >
              <v-list-item value="en" rounded="lg" @click="locale = 'en'">
                <v-list-item-title class="text-body-2 font-weight-medium">
                  🇬🇧 English
                </v-list-item-title>
              </v-list-item>
              <v-list-item value="km" rounded="lg" @click="locale = 'km'">
                <v-list-item-title class="text-body-2 font-weight-medium">
                  🇰🇭 ភាសាខ្មែរ
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-btn>

        <!-- Theme -->
        <v-btn
          :icon="theme === 'light' ? 'mdi-weather-night' : 'mdi-weather-sunny'"
          variant="text"
          size="small"
          rounded="lg"
          @click="theme = theme === 'light' ? 'dark' : 'light'"
        />

        <!-- CTA -->
        <v-btn
          color="primary"
          rounded="xl"
          variant="flat"
          size="small"
          class="d-none d-md-flex px-5 ml-1"
          href="#contact"
        >
          {{ t('landing.hero.cta_primary') }}
        </v-btn>

        <!-- Hamburger -->
        <v-app-bar-nav-icon class="d-md-none" @click="drawer = !drawer" />
      </div>
    </v-app-bar>

    <v-main>
      <!-- ── HERO ──────────────────────────────────────────────────────── -->
      <section class="hero-section">
        <div class="hero-bg-orb orb-1" />
        <div class="hero-bg-orb orb-2" />
        <div class="hero-bg-orb orb-3" />

        <v-container>
          <v-row justify="center">
            <v-col cols="12" md="9" lg="7" class="text-center">
              <div
                class="hero-badge mb-7"
                data-aos="fade-down"
                data-aos-delay="0"
              >
                <span class="pulse-dot" />
                {{ t('landing.hero.badge') }}
              </div>

              <h1
                class="hero-title mb-6"
                data-aos="fade-up"
                data-aos-delay="100"
              >
                {{ t('landing.hero.title1') }}
                <br />
                <span class="gradient-text">
                  {{ t('landing.hero.title2') }}
                </span>
              </h1>

              <p class="hero-sub mb-10" data-aos="fade-up" data-aos-delay="200">
                {{ t('landing.hero.sub') }}
              </p>

              <div
                class="d-flex gap-3 justify-center flex-wrap"
                data-aos="fade-up"
                data-aos-delay="300"
              >
                <v-btn
                  color="primary"
                  size="x-large"
                  rounded="xl"
                  variant="flat"
                  class="hero-btn px-10"
                  append-icon="mdi-arrow-right"
                  href="#contact"
                >
                  {{ t('landing.hero.cta_primary') }}
                </v-btn>
                <v-btn
                  size="x-large"
                  rounded="xl"
                  variant="outlined"
                  class="px-10"
                  href="#features"
                >
                  {{ t('landing.hero.cta_secondary') }}
                </v-btn>
              </div>

              <!-- Stats row -->
              <v-row
                justify="center"
                dense
                class="mt-16"
                data-aos="fade-up"
                data-aos-delay="400"
              >
                <v-col v-for="s in stats" :key="s.key" cols="6" sm="3">
                  <div class="stat-pill">
                    <div class="stat-num">{{ s.num }}</div>
                    <div class="stat-label">{{ t(s.key) }}</div>
                  </div>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <!-- ── BIZ TYPES ─────────────────────────────────────────────────── -->
      <section class="section-pad bg-soft" id="features">
        <v-container>
          <div class="text-center mb-12" data-aos="fade-up">
            <div class="section-tag">{{ t('landing.biz.tag') }}</div>
            <h2 class="section-title">{{ t('landing.biz.title') }}</h2>
          </div>
          <v-row justify="center" dense>
            <v-col
              v-for="(biz, i) in bizTypes"
              :key="biz.key"
              cols="6"
              sm="4"
              md="2"
              data-aos="zoom-in"
              :data-aos-delay="i * 60"
            >
              <v-card
                rounded="xl"
                border
                variant="flat"
                class="biz-card pa-5 text-center"
                hover
              >
                <div class="biz-icon">{{ biz.icon }}</div>
                <div class="biz-label">{{ t(biz.key) }}</div>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <!-- ── RESTAURANT POS ────────────────────────────────────────────── -->
      <section class="section-pad" id="pos">
        <v-container>
          <v-row align="center" class="feature-row">
            <!-- Text -->
            <v-col cols="12" md="5" data-aos="fade-right">
              <div class="section-tag">
                {{ t('landing.restaurant_pos.tag') }}
              </div>
              <h2 class="section-title">
                {{ t('landing.restaurant_pos.title') }}
              </h2>
              <p class="section-sub mb-7">
                {{ t('landing.restaurant_pos.sub') }}
              </p>
              <div class="feature-checks">
                <div
                  v-for="k in ['f1', 'f2', 'f3', 'f4']"
                  :key="k"
                  class="check-item"
                >
                  <div class="check-icon">
                    <v-icon icon="mdi-check" size="13" color="primary" />
                  </div>
                  <span>{{ t(`landing.restaurant_pos.${k}`) }}</span>
                </div>
              </div>
            </v-col>

            <!-- Visual -->
            <v-col cols="12" md="7" data-aos="fade-left" data-aos-delay="100">
              <div class="visual-wrapper visual-blue">
                <!-- POS Mock -->
                <div class="pos-mock">
                  <div class="pos-topbar">
                    <div class="pos-dots">
                      <span />
                      <span />
                      <span />
                    </div>
                    <span class="pos-title">Restaurant POS</span>
                  </div>
                  <div class="pos-body">
                    <!-- Product grid -->
                    <div class="product-grid">
                      <div
                        v-for="(p, i) in mockProducts"
                        :key="i"
                        class="product-tile"
                        :class="{ selected: i === selectedMock }"
                        @click="selectedMock = i"
                      >
                        <div
                          class="tile-dot"
                          :style="`background:${p.color}`"
                        />
                        <div class="tile-name">{{ p.name }}</div>
                        <div class="tile-price">{{ p.price }}</div>
                      </div>
                    </div>
                    <!-- Cart -->
                    <div class="mock-cart">
                      <div class="cart-title">Current Order</div>
                      <div
                        class="cart-item"
                        v-for="o in mockOrder"
                        :key="o.name"
                      >
                        <span>{{ o.name }}</span>
                        <span class="cart-price">{{ o.price }}</span>
                      </div>
                      <div class="cart-divider" />
                      <div class="cart-total">
                        <span>Total</span>
                        <span>$12.50</span>
                      </div>
                      <div class="pay-btn">Pay Now →</div>
                    </div>
                  </div>
                </div>
                <!-- Floating chips -->
                <div class="float-chip chip-1">
                  <v-icon icon="mdi-check-circle" color="success" size="14" />
                  Order confirmed
                </div>
                <div class="float-chip chip-2">
                  <v-icon icon="mdi-lightning-bolt" color="warning" size="14" />
                  Avg 0.8s/order
                </div>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <!-- ── MART POS ──────────────────────────────────────────────────── -->
      <section class="section-pad bg-soft">
        <v-container>
          <v-row align="center" class="feature-row flex-md-row-reverse">
            <!-- Text -->
            <v-col cols="12" md="5" data-aos="fade-left">
              <div class="section-tag">{{ t('landing.mart_pos.tag') }}</div>
              <h2 class="section-title">{{ t('landing.mart_pos.title') }}</h2>
              <p class="section-sub mb-7">{{ t('landing.mart_pos.sub') }}</p>
              <div class="feature-checks">
                <div
                  v-for="k in ['f1', 'f2', 'f3', 'f4']"
                  :key="k"
                  class="check-item"
                >
                  <div class="check-icon">
                    <v-icon icon="mdi-check" size="13" color="primary" />
                  </div>
                  <span>{{ t(`landing.mart_pos.${k}`) }}</span>
                </div>
              </div>
              <!-- Unit demo -->
              <div class="unit-demo mt-8">
                <div class="unit-demo-title">Multi-unit pricing</div>
                <div class="unit-pills">
                  <div
                    v-for="u in unitDemo"
                    :key="u.label"
                    class="unit-pill"
                    :class="{ active: u.active }"
                  >
                    <span class="u-label">{{ u.label }}</span>
                    <span class="u-price">{{ u.price }}</span>
                  </div>
                </div>
              </div>
            </v-col>

            <!-- Visual -->
            <v-col cols="12" md="7" data-aos="fade-right" data-aos-delay="100">
              <div class="visual-wrapper visual-indigo">
                <!-- Inventory cards grid -->
                <div class="inv-grid">
                  <div v-for="p in martProducts" :key="p.name" class="inv-card">
                    <div class="inv-img" :style="`background:${p.bg}`">
                      {{ p.emoji }}
                    </div>
                    <div class="inv-info">
                      <div class="inv-name">{{ p.name }}</div>
                      <div class="inv-stock">
                        <span :class="`stock-dot ${p.status}`" />
                        {{ p.stock }}
                      </div>
                    </div>
                    <div class="inv-price">{{ p.price }}</div>
                  </div>
                </div>
                <!-- Floating receipt -->
                <div class="float-receipt">
                  <div class="receipt-header">Sale #1047</div>
                  <div class="receipt-row">
                    <span>Beer × Box</span>
                    <span>$12</span>
                  </div>
                  <div class="receipt-row">
                    <span>Red Bull × 3</span>
                    <span>$4.50</span>
                  </div>
                  <div class="receipt-divider" />
                  <div class="receipt-total">
                    <span>Total</span>
                    <span>$16.50</span>
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <!-- ── INVENTORY ─────────────────────────────────────────────────── -->
      <section class="section-pad" id="inventory">
        <v-container>
          <v-row align="center" class="feature-row">
            <v-col cols="12" md="5" data-aos="fade-right">
              <div class="section-tag">{{ t('landing.inventory.tag') }}</div>
              <h2 class="section-title">{{ t('landing.inventory.title') }}</h2>
              <p class="section-sub mb-7">{{ t('landing.inventory.sub') }}</p>
              <div class="feature-checks">
                <div
                  v-for="k in ['f1', 'f2', 'f3', 'f4']"
                  :key="k"
                  class="check-item"
                >
                  <div class="check-icon">
                    <v-icon icon="mdi-check" size="13" color="primary" />
                  </div>
                  <span>{{ t(`landing.inventory.${k}`) }}</span>
                </div>
              </div>
            </v-col>

            <v-col cols="12" md="7" data-aos="fade-left" data-aos-delay="100">
              <div class="visual-wrapper visual-teal">
                <div class="chart-mock">
                  <div class="chart-header">
                    <span class="chart-title">Stock Movements</span>
                    <span class="chart-badge">This Month</span>
                  </div>
                  <!-- Bars -->
                  <div class="chart-bars">
                    <div v-for="(b, i) in chartBars" :key="i" class="bar-wrap">
                      <div
                        class="bar"
                        :style="`height:${b.h}%`"
                        :class="{ 'bar-highlight': b.h === 90 }"
                      />
                      <div class="bar-label">{{ b.l }}</div>
                    </div>
                  </div>
                  <!-- Stock list -->
                  <div class="stock-list">
                    <div v-for="s in stockList" :key="s.name" class="stock-row">
                      <div class="d-flex align-center gap-2">
                        <v-icon :icon="s.icon" :color="s.color" size="15" />
                        <span class="stock-name">{{ s.name }}</span>
                      </div>
                      <div class="d-flex align-center gap-2">
                        <div class="stock-bar-bg">
                          <div
                            class="stock-bar-fill"
                            :style="`width:${s.pct}%;background:${s.barColor}`"
                          />
                        </div>
                        <span class="stock-qty" :style="`color:${s.color}`">
                          {{ s.qty }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <!-- ── FEATURE CARDS ──────────────────────────────────────────────── -->
      <section class="section-pad bg-soft">
        <v-container>
          <div class="text-center mb-12" data-aos="fade-up">
            <div class="section-tag">{{ t('landing.features.tag') }}</div>
            <h2 class="section-title">{{ t('landing.features.title') }}</h2>
          </div>
          <v-row dense>
            <v-col
              v-for="(f, i) in featureCards"
              :key="f.titleKey"
              cols="12"
              sm="6"
              md="4"
              data-aos="fade-up"
              :data-aos-delay="i * 60"
            >
              <v-card
                rounded="xl"
                border
                variant="flat"
                class="feature-card pa-7 fill-height"
                hover
              >
                <v-avatar
                  :color="f.color"
                  variant="tonal"
                  rounded="xl"
                  size="52"
                  class="mb-5"
                >
                  <v-icon :icon="f.icon" size="24" />
                </v-avatar>
                <div class="text-body-1 font-weight-bold mb-2">
                  {{ t(f.titleKey) }}
                </div>
                <div
                  class="text-body-2 text-medium-emphasis"
                  style="line-height: 1.7"
                >
                  {{ t(f.descKey) }}
                </div>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <!-- ── CTA ───────────────────────────────────────────────────────── -->
      <section class="section-pad" id="contact">
        <v-container>
          <div class="cta-block" data-aos="zoom-in">
            <div class="cta-orb cta-orb-1" />
            <div class="cta-orb cta-orb-2" />
            <div class="cta-inner text-center">
              <h2 class="cta-title text-white mb-3">
                {{ t('landing.cta.title') }}
              </h2>
              <p
                class="text-white mb-8"
                style="opacity: 0.75; font-size: 1.05rem"
              >
                {{ t('landing.cta.sub') }}
              </p>
              <v-btn
                color="white"
                variant="flat"
                size="x-large"
                rounded="xl"
                href="mailto:hello@smartstore.kh"
                class="px-12 font-weight-bold text-primary cta-btn"
              >
                {{ t('landing.cta.btn') }}
              </v-btn>
            </div>
          </div>
        </v-container>
      </section>

      <!-- ── FOOTER ────────────────────────────────────────────────────── -->
      <v-footer border="t" class="py-6">
        <v-container>
          <div
            class="d-flex align-center justify-space-between flex-wrap gap-4"
          >
            <div class="logo-text">
              Smart
              <span class="accent-text">Store</span>
            </div>
            <div class="d-flex gap-4">
              <a
                v-for="link in navLinks"
                :key="link.href"
                :href="link.href"
                class="footer-link"
              >
                {{ t(link.key) }}
              </a>
            </div>
            <div class="text-caption text-medium-emphasis">
              {{ t('landing.footer.copy') }}
            </div>
          </div>
        </v-container>
      </v-footer>
    </v-main>
  </v-app>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { useI18n } from 'vue-i18n'
  import AOS from 'aos'
  import 'aos/dist/aos.css'

  const { t, locale } = useI18n()
  const theme = ref('light')
  const drawer = ref(false)
  const selectedMock = ref(1)

  onMounted(() => {
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true })
  })

  const navLinks = [
    { href: '#features', key: 'landing.nav.features' },
    { href: '#pos', key: 'landing.nav.pos' },
    { href: '#inventory', key: 'landing.nav.inventory' },
    { href: '#contact', key: 'landing.nav.contact' }
  ]

  const stats = [
    { num: '5+', key: 'landing.stats.biz_types' },
    { num: '∞', key: 'landing.stats.products' },
    { num: 'Live', key: 'landing.stats.tracking' },
    { num: 'Multi', key: 'landing.stats.branches' }
  ]

  const bizTypes = [
    { icon: '🍜', key: 'landing.biz.restaurant' },
    { icon: '☕', key: 'landing.biz.cafe' },
    { icon: '🏪', key: 'landing.biz.minimart' },
    { icon: '🏬', key: 'landing.biz.retail' },
    { icon: '🥐', key: 'landing.biz.bakery' }
    // { icon: '🚚', key: 'landing.biz.food_truck'  },
  ]

  const mockProducts = [
    { name: 'Noodle', price: '$4.50', color: '#93c5fd' },
    { name: 'Coffee', price: '$2.50', color: '#c4b5fd' },
    { name: 'Steak', price: '$12', color: '#fca5a5' },
    { name: 'Juice', price: '$3', color: '#6ee7b7' },
    { name: 'Rice', price: '$5', color: '#fde68a' },
    { name: 'Soup', price: '$4', color: '#a5b4fc' }
  ]

  const mockOrder = [
    { name: 'Beef Noodle ×2', price: '$9.00' },
    { name: 'Iced Coffee ×1', price: '$2.50' },
    { name: 'Spring Roll ×1', price: '$1.00' }
  ]

  const unitDemo = [
    { label: 'Can ×1', price: '$0.80', active: false },
    { label: 'Pack ×6', price: '$4.50', active: true },
    { label: 'Box ×24', price: '$16', active: false },
    { label: 'Carton ×48', price: '$30', active: false }
  ]

  const martProducts = [
    {
      name: 'Cambodian Beer',
      emoji: '🍺',
      bg: '#dbeafe',
      stock: '48 boxes',
      price: '$12/box',
      status: 'ok'
    },
    {
      name: 'Red Bull',
      emoji: '⚡',
      bg: '#fef9c3',
      stock: '120 cans',
      price: '$1.50/can',
      status: 'ok'
    },
    {
      name: 'Heineken',
      emoji: '🍻',
      bg: '#dcfce7',
      stock: '3 boxes',
      price: '$14/box',
      status: 'low'
    },
    {
      name: 'Mineral Water',
      emoji: '💧',
      bg: '#e0f2fe',
      stock: 'Out',
      price: '$0.50',
      status: 'out'
    }
  ]

  const chartBars = [
    { h: 38, l: 'W1' },
    { h: 55, l: 'W2' },
    { h: 48, l: 'W3' },
    { h: 90, l: 'W4' },
    { h: 70, l: 'W5' },
    { h: 82, l: 'W6' },
    { h: 60, l: 'W7' }
  ]

  const stockList = [
    {
      name: 'Cambodian Beer',
      icon: 'mdi-check-circle',
      color: '#22c55e',
      barColor: '#22c55e',
      qty: '48',
      pct: 80
    },
    {
      name: 'Red Bull',
      icon: 'mdi-check-circle',
      color: '#22c55e',
      barColor: '#22c55e',
      qty: '120',
      pct: 95
    },
    {
      name: 'Heineken',
      icon: 'mdi-alert',
      color: '#f59e0b',
      barColor: '#f59e0b',
      qty: '3',
      pct: 12
    },
    {
      name: 'Mineral Water',
      icon: 'mdi-close-circle',
      color: '#ef4444',
      barColor: '#ef4444',
      qty: '0',
      pct: 0
    }
  ]

  const featureCards = [
    {
      icon: 'mdi-chart-bar',
      color: 'primary',
      titleKey: 'landing.features.sales_title',
      descKey: 'landing.features.sales_desc'
    },
    {
      icon: 'mdi-account-group-outline',
      color: 'indigo',
      titleKey: 'landing.features.staff_title',
      descKey: 'landing.features.staff_desc'
    },
    {
      icon: 'mdi-store-outline',
      color: 'teal',
      titleKey: 'landing.features.branch_title',
      descKey: 'landing.features.branch_desc'
    },
    {
      icon: 'mdi-trophy-outline',
      color: 'amber',
      titleKey: 'landing.features.perf_title',
      descKey: 'landing.features.perf_desc'
    },
    {
      icon: 'mdi-truck-delivery-outline',
      color: 'purple',
      titleKey: 'landing.features.supplier_title',
      descKey: 'landing.features.supplier_desc'
    },
    {
      icon: 'mdi-translate',
      color: 'cyan',
      titleKey: 'landing.features.lang_title',
      descKey: 'landing.features.lang_desc'
    }
  ]
</script>

<style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;0,900;1,400&family=Noto+Sans+Khmer:wght@400;600;700;900&display=swap');

  * {
    font-family: 'Plus Jakarta Sans', 'Noto Sans Khmer', sans-serif;
  }

  /* ── Navbar ── */
  .glass-nav {
    position: sticky m !important;
    top: 0;
    background: rgba(var(--v-theme-surface), 0.82) !important;
    backdrop-filter: blur(16px);
    border-bottom: 1px solid rgba(var(--v-theme-on-surface), 0.06) !important;
  }
  .logo-text {
    font-size: 1.45rem;
    font-weight: 900;
    letter-spacing: -1px;
    color: rgb(var(--v-theme-primary));
  }
  .accent-text {
    color: #f59e0b;
  }
  .nav-link {
    font-size: 0.875rem;
    font-weight: 600;
    color: rgba(var(--v-theme-on-surface), 0.55);
    text-decoration: none;
    transition: color 0.15s;
  }
  .nav-link:hover {
    color: rgb(var(--v-theme-primary));
  }
  .lang-btn {
    font-weight: 700;
    font-size: 0.8rem;
  }
  .gap-7 {
    gap: 28px;
  }
  .gap-4 {
    gap: 16px;
  }
  .gap-3 {
    gap: 12px;
  }
  .gap-2 {
    gap: 8px;
  }

  /* ── Hero ── */
  .hero-section {
    position: relative;
    padding: 120px 0 90px;
    overflow: hidden;
  }
  .hero-bg-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    z-index: 0;
  }
  .orb-1 {
    width: 500px;
    height: 500px;
    background: rgba(37, 99, 235, 0.07);
    top: -150px;
    right: -100px;
  }
  .orb-2 {
    width: 400px;
    height: 400px;
    background: rgba(245, 158, 11, 0.05);
    bottom: -100px;
    left: -80px;
  }
  .orb-3 {
    width: 300px;
    height: 300px;
    background: rgba(99, 102, 241, 0.06);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(var(--v-theme-primary-rgb, 37, 99, 235), 0.08);
    border: 1px solid rgba(37, 99, 235, 0.18);
    color: rgb(var(--v-theme-primary));
    font-size: 0.8rem;
    font-weight: 700;
    padding: 7px 18px;
    border-radius: 999px;
    letter-spacing: 0.3px;
  }
  .pulse-dot {
    width: 7px;
    height: 7px;
    background: rgb(var(--v-theme-primary));
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0%,
    100% {
      transform: scale(1);
      opacity: 1;
    }
    50% {
      transform: scale(1.6);
      opacity: 0.5;
    }
  }

  .hero-title {
    font-size: clamp(2.6rem, 6vw, 4.8rem);
    font-weight: 900;
    line-height: 1.08;
    letter-spacing: -2.5px;
    position: relative;
    z-index: 1;
  }
  .gradient-text {
    background: linear-gradient(120deg, #2563eb 0%, #7c3aed 50%, #db2777 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .hero-sub {
    font-size: 1.15rem;
    max-width: 560px;
    margin: 0 auto;
    opacity: 0.65;
    line-height: 1.7;
    position: relative;
    z-index: 1;
  }
  .hero-btn {
    box-shadow: 0 8px 32px rgba(37, 99, 235, 0.35) !important;
  }
  .stat-pill {
    background: rgba(var(--v-theme-on-surface), 0.035);
    border: 1px solid rgba(var(--v-theme-on-surface), 0.07);
    border-radius: 16px;
    padding: 16px 8px;
    text-align: center;
  }
  .stat-num {
    font-size: 1.9rem;
    font-weight: 900;
    letter-spacing: -1px;
    color: rgb(var(--v-theme-primary));
  }
  .stat-label {
    font-size: 0.72rem;
    opacity: 0.6;
    margin-top: 3px;
    font-weight: 600;
  }

  /* ── Sections ── */
  .section-pad {
    padding: 88px 0;
  }
  .bg-soft {
    background: rgba(var(--v-theme-surface-variant), 0.25);
  }
  .section-tag {
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: rgb(var(--v-theme-primary));
    text-transform: uppercase;
    margin-bottom: 10px;
  }
  .section-title {
    font-size: clamp(1.7rem, 3.5vw, 2.6rem);
    font-weight: 900;
    letter-spacing: -1px;
    line-height: 1.12;
    margin-bottom: 14px;
  }
  .section-sub {
    font-size: 0.95rem;
    opacity: 0.6;
    line-height: 1.75;
    max-width: 480px;
  }
  .feature-row {
    gap: 0;
  }

  /* ── Feature checks ── */
  .feature-checks {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .check-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 0.875rem;
    line-height: 1.5;
    opacity: 0.75;
  }
  .check-icon {
    width: 22px;
    height: 22px;
    background: rgba(37, 99, 235, 0.1);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
  }

  /* ── Biz cards ── */
  .biz-card {
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: default;
  }
  .biz-card:hover {
    transform: translateY(-6px);
    border-color: rgb(var(--v-theme-primary)) !important;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08) !important;
  }
  .biz-icon {
    font-size: 2.2rem;
    margin-bottom: 10px;
  }
  .biz-label {
    font-size: 0.82rem;
    font-weight: 700;
  }

  /* ── Visual wrappers ── */
  .visual-wrapper {
    border-radius: 24px;
    padding: 32px;
    position: relative;
    overflow: hidden;
    min-height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .visual-blue {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
  }
  .visual-indigo {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
  }
  .visual-teal {
    background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
  }

  /* ── POS Mock ── */
  .pos-mock {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.14);
    overflow: hidden;
    width: 100%;
    max-width: 440px;
  }
  .pos-topbar {
    background: #2563eb;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .pos-dots {
    display: flex;
    gap: 5px;
  }
  .pos-dots span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
  }
  .pos-title {
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
  }
  .pos-body {
    display: flex;
    gap: 0;
  }
  .product-grid {
    flex: 1;
    padding: 12px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
  }
  .product-tile {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.15s;
    text-align: center;
  }
  .product-tile.selected {
    border-color: #2563eb;
    background: #eff6ff;
  }
  .tile-dot {
    width: 24px;
    height: 24px;
    border-radius: 8px;
    margin: 0 auto 4px;
  }
  .tile-name {
    font-size: 0.62rem;
    font-weight: 700;
    color: #334155;
  }
  .tile-price {
    font-size: 0.62rem;
    color: #2563eb;
    font-weight: 600;
  }

  .mock-cart {
    width: 130px;
    background: #1e40af;
    padding: 12px;
    color: #fff;
  }
  .cart-title {
    font-size: 0.62rem;
    opacity: 0.6;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 8px;
  }
  .cart-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.6rem;
    margin-bottom: 4px;
    opacity: 0.85;
  }
  .cart-price {
    font-weight: 700;
  }
  .cart-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    margin: 8px 0;
  }
  .cart-total {
    display: flex;
    justify-content: space-between;
    font-size: 0.7rem;
    font-weight: 800;
  }
  .pay-btn {
    margin-top: 10px;
    background: #fff;
    color: #1e40af;
    border-radius: 8px;
    padding: 6px 0;
    text-align: center;
    font-size: 0.65rem;
    font-weight: 800;
    cursor: pointer;
  }

  /* ── Floating chips ── */
  .float-chip {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 5px;
    background: #fff;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.72rem;
    font-weight: 700;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
  }
  .chip-1 {
    top: 16px;
    right: 16px;
  }
  .chip-2 {
    bottom: 16px;
    left: 16px;
  }

  /* ── Unit demo ── */
  .unit-demo {
    background: rgba(var(--v-theme-on-surface), 0.03);
    border: 1px solid rgba(var(--v-theme-on-surface), 0.07);
    border-radius: 16px;
    padding: 16px 20px;
  }
  .unit-demo-title {
    font-size: 0.72rem;
    font-weight: 700;
    opacity: 0.5;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
  }
  .unit-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
  }
  .unit-pill {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 16px;
    border-radius: 12px;
    border: 1.5px solid rgba(var(--v-theme-on-surface), 0.1);
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.15s;
  }
  .unit-pill.active {
    border-color: rgb(var(--v-theme-primary));
    background: rgba(37, 99, 235, 0.06);
  }
  .u-label {
    font-weight: 700;
    color: rgb(var(--v-theme-on-surface));
  }
  .u-price {
    font-size: 0.68rem;
    color: rgb(var(--v-theme-primary));
    font-weight: 600;
    margin-top: 2px;
  }

  /* ── Inventory cards ── */
  .inv-grid {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
    max-width: 340px;
  }
  .inv-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border-radius: 12px;
    padding: 10px 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  }
  .inv-img {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
  }
  .inv-info {
    flex: 1;
  }
  .inv-name {
    font-size: 0.72rem;
    font-weight: 700;
    color: #1e293b;
  }
  .inv-stock {
    font-size: 0.65rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .inv-price {
    font-size: 0.72rem;
    font-weight: 800;
    color: #2563eb;
    white-space: nowrap;
  }
  .stock-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
  }
  .stock-dot.ok {
    background: #22c55e;
  }
  .stock-dot.low {
    background: #f59e0b;
  }
  .stock-dot.out {
    background: #ef4444;
  }

  /* ── Float receipt ── */
  .float-receipt {
    position: absolute;
    bottom: 16px;
    right: 16px;
    background: #fff;
    border-radius: 12px;
    padding: 12px 14px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    min-width: 140px;
  }
  .receipt-header {
    font-size: 0.65rem;
    font-weight: 800;
    color: #1e40af;
    margin-bottom: 6px;
  }
  .receipt-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.62rem;
    color: #475569;
    margin-bottom: 3px;
  }
  .receipt-divider {
    border-top: 1px solid #f1f5f9;
    margin: 6px 0;
  }
  .receipt-total {
    display: flex;
    justify-content: space-between;
    font-size: 0.72rem;
    font-weight: 800;
    color: #1e293b;
  }

  /* ── Chart mock ── */
  .chart-mock {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.1);
  }
  .chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
  }
  .chart-title {
    font-size: 0.82rem;
    font-weight: 800;
    color: #1e293b;
  }
  .chart-badge {
    font-size: 0.65rem;
    background: #f0fdf4;
    color: #16a34a;
    padding: 3px 8px;
    border-radius: 999px;
    font-weight: 700;
  }
  .chart-bars {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 80px;
    margin-bottom: 16px;
  }
  .bar-wrap {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    height: 100%;
  }
  .bar {
    width: 100%;
    background: rgba(20, 184, 166, 0.35);
    border-radius: 4px 4px 0 0;
    transition: height 0.3s;
  }
  .bar-highlight {
    background: #14b8a6 !important;
  }
  .bar-label {
    font-size: 0.58rem;
    color: #94a3b8;
    font-weight: 600;
  }
  .stock-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .stock-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .stock-name {
    font-size: 0.72rem;
    font-weight: 600;
    color: #334155;
  }
  .stock-bar-bg {
    width: 72px;
    height: 5px;
    background: #f1f5f9;
    border-radius: 99px;
    overflow: hidden;
  }
  .stock-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 0.5s;
  }
  .stock-qty {
    font-size: 0.7rem;
    font-weight: 800;
    min-width: 24px;
    text-align: right;
  }

  /* ── Feature cards ── */
  .feature-card {
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.07) !important;
  }

  /* ── CTA ── */
  .cta-block {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 50%, #7c3aed 100%);
    border-radius: 28px;
    padding: 80px 40px;
  }
  .cta-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
  }
  .cta-orb-1 {
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.05);
    top: -150px;
    right: -100px;
  }
  .cta-orb-2 {
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.04);
    bottom: -100px;
    left: -60px;
  }
  .cta-inner {
    position: relative;
    z-index: 1;
  }
  .cta-title {
    font-size: clamp(1.8rem, 3.5vw, 2.8rem);
    font-weight: 900;
    letter-spacing: -1px;
  }
  .cta-btn {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2) !important;
  }

  /* ── Footer ── */
  .footer-link {
    font-size: 0.8rem;
    font-weight: 600;
    color: rgba(var(--v-theme-on-surface), 0.45);
    text-decoration: none;
    transition: color 0.15s;
  }
  .footer-link:hover {
    color: rgb(var(--v-theme-primary));
  }

  /* ── Responsive ── */
  @media (max-width: 768px) {
    .hero-section {
      padding: 80px 0 60px;
    }
    .section-pad {
      padding: 64px 0;
    }
    .hero-title {
      letter-spacing: -1.5px;
    }
  }
</style>
