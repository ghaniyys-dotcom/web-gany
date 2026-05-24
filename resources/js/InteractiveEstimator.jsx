import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

export default function InteractiveEstimator({ pricing }) {
  const BASE_PRICES = pricing?.base_prices || {
    landing: 3000000,
    compro: 6000000,
    custom: 12000000
  };

  const FEATURE_PRICES = pricing?.feature_prices || {
    animation: 1500000,
    admin: 2500000,
    seo: 1000000,
    multilang: 2000000
  };

  const FEATURE_LABELS = pricing?.feature_labels || {
    animation: 'Premium Animations (+Rp 1.5jt)',
    admin: 'Custom CMS / Admin (+Rp 2.5jt)',
    seo: 'SEO Pack (+Rp 1jt)',
    multilang: 'Multi-Language (+Rp 2jt)'
  };

  const [calcType, setCalcType] = useState('landing');
  const [features, setFeatures] = useState({
    animation: false,
    admin: false,
    seo: false,
    multilang: false
  });
  
  const [applied, setApplied] = useState(false);

  // Calculate target price based on current selections
  const targetPrice = BASE_PRICES[calcType] + Object.keys(features).reduce((acc, key) => {
    return acc + (features[key] ? FEATURE_PRICES[key] : 0);
  }, 0);

  // Smooth odometer counter tick logic (60/120 FPS frame tick)
  const [displayPrice, setDisplayPrice] = useState(BASE_PRICES.landing);
  useEffect(() => {
    let start = displayPrice;
    let end = targetPrice;
    if (start === end) return;
    
    let startTime = null;
    const duration = 650; // 650ms smooth rolling speed
    
    const step = (timestamp) => {
      if (!startTime) startTime = timestamp;
      const progress = Math.min((timestamp - startTime) / duration, 1);
      
      // Gorgeous custom elastic cubic-ease-out curve
      const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
      const current = Math.floor(start + (end - start) * ease);
      
      setDisplayPrice(current);
      if (progress < 1) {
        requestAnimationFrame(step);
      }
    };
    
    const animId = requestAnimationFrame(step);
    return () => cancelAnimationFrame(animId);
  }, [targetPrice]);

  const handleFeatureToggle = (key) => {
    setFeatures(prev => ({ ...prev, [key]: !prev[key] }));
    setApplied(false);
  };

  const handleTypeSelect = (type) => {
    setCalcType(type);
    setApplied(false);
  };

  const formatIDR = (num) => {
    return 'Rp ' + num.toLocaleString('id-ID');
  };

  const formatPriceShort = (val) => {
    if (val >= 1000000) {
      const jt = val / 1000000;
      const formattedJt = jt % 1 === 0 ? jt : jt.toFixed(1).replace('.', ',');
      return `Rp ${formattedJt} jt+`;
    } else {
      const rb = val / 1000;
      const formattedRb = rb % 1 === 0 ? rb : rb.toFixed(1).replace('.', ',');
      return `Rp ${formattedRb} rb+`;
    }
  };

  // Sync inputs with the main PHP contact form elements
  const handleApply = () => {
    const formBudgetSelect = document.getElementById('form-budget-select');
    const formMessageArea = document.getElementById('form-message-area');
    
    if (formBudgetSelect && formMessageArea) {
      // Dynamic budget selection matching logic based on options upper bounds
      const selectOptions = Array.from(formBudgetSelect.options).filter(opt => opt.value !== "");
      if (selectOptions.length > 0) {
        const parsedTiers = selectOptions.map((opt, idx) => {
          const str = opt.value.toLowerCase();
          let maxVal = Infinity;
          
          if (str.includes('juta') || str.includes('jt')) {
            const matches = str.match(/[\d,\.]+/g);
            if (matches) {
              const lastNumStr = matches[matches.length - 1].replace(',', '.');
              const val = parseFloat(lastNumStr);
              if (!isNaN(val)) maxVal = val * 1000000;
            }
          } else if (str.includes('ribu') || str.includes('rb')) {
            const matches = str.match(/[\d,\.]+/g);
            if (matches) {
              const lastNumStr = matches[matches.length - 1].replace(',', '.');
              const val = parseFloat(lastNumStr);
              if (!isNaN(val)) maxVal = val * 1000;
            }
          }
          
          return { value: opt.value, maxVal, index: idx };
        });

        // Find matching option where targetPrice <= maxVal
        const matched = parsedTiers.find(tier => targetPrice <= tier.maxVal) || parsedTiers[parsedTiers.length - 1];
        if (matched) {
          formBudgetSelect.value = matched.value;
        }
      }

      const typeLabel = calcType === 'landing' ? 'Landing Page' : calcType === 'compro' ? 'Company Profile' : 'Custom SaaS';
      const featuresList = Object.keys(features)
        .filter(k => features[k])
        .map(k => k === 'animation' ? 'Premium Animations' : k === 'admin' ? 'Custom CMS / Admin' : k === 'seo' ? 'SEO Pack' : 'Multi-Language');

      let text = `Halo Gany, saya butuh website ${typeLabel}.\n`;
      if (featuresList.length > 0) {
        text += `Fitur tambahan: ${featuresList.join(', ')}.\n`;
      }
      text += `Estimasi kalkulator: ${formatIDR(targetPrice)}.\n\nBerikut detail tambahan project saya...`;

      formMessageArea.value = text;
      formMessageArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
      formMessageArea.focus();
      
      // Trigger global audio hover tick if available
      if (window._audioEngine && typeof window._audioEngine.playHoverTick === 'function') {
        window._audioEngine.playHoverTick();
      }

      setApplied(true);
      setTimeout(() => setApplied(false), 3000);
    }
  };

  return (
    <div className="calculator-widget" style={{ marginBottom: '24px', padding: '24px', borderRadius: '24px', background: 'rgba(255, 255, 255, 0.02)', border: '1px solid rgba(255, 255, 255, 0.05)', boxShadow: '0 20px 40px rgba(0,0,0,0.3)' }}>
      <span className="founder-eyebrow" style={{ color: '#ff6a1a', fontSize: '11px', marginBottom: '12px', display: 'block', fontWeight: 400, letterSpacing: '0.12em' }}>✦ ESTIMATOR BIAYA PROYEK</span>
      <h4 style={{ margin: '0 0 20px', fontSize: '20px', color: '#fff', fontWeight: 300, letterSpacing: '-0.02em' }}>Kalkulator Budget Interaktif</h4>
      
      {/* 1. Pilih Tipe Website */}
      <div style={{ marginBottom: '20px' }}>
        <span style={{ fontSize: '13px', color: '#a1a1aa', display: 'block', marginBottom: '10px', fontWeight: 500 }}>1. Pilih Tipe Website</span>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(110px, 1fr))', gap: '10px' }}>
          {[
            { id: 'landing', label: 'Landing Page', price: formatPriceShort(BASE_PRICES.landing) },
            { id: 'compro', label: 'Company Profile', price: formatPriceShort(BASE_PRICES.compro) },
            { id: 'custom', label: 'Custom SaaS', price: formatPriceShort(BASE_PRICES.custom) }
          ].map((type) => {
            const isActive = calcType === type.id;
            return (
              <motion.div
                key={type.id}
                onClick={() => handleTypeSelect(type.id)}
                whileHover={{ scale: 1.03 }}
                whileTap={{ scale: 0.97 }}
                className={`calc-card ${isActive ? 'active-glow' : ''}`}
                style={{
                  cursor: 'pointer',
                  padding: '16px 12px',
                  borderRadius: '16px',
                  border: '1px solid',
                  borderColor: isActive ? '#ff6a1a' : 'rgba(255, 255, 255, 0.06)',
                  background: isActive ? 'rgba(255, 106, 26, 0.06)' : 'rgba(255, 255, 255, 0.01)',
                  boxShadow: isActive ? '0 0 20px rgba(255, 106, 26, 0.15)' : 'none',
                  textAlign: 'center',
                  transition: 'border-color 0.3s, background-color 0.3s, box-shadow 0.3s'
                }}
              >
                <strong style={{ display: 'block', fontSize: '13px', color: isActive ? '#fff' : '#d4d4d8', fontWeight: 400 }}>{type.label}</strong>
                <span style={{ fontSize: '11px', color: isActive ? '#ff6a1a' : '#71717a', marginTop: '4px', display: 'block', fontWeight: 400 }}>{type.price}</span>
              </motion.div>
            );
          })}
        </div>
      </div>

      {/* 2. Fitur Tambahan */}
      <div style={{ marginBottom: '24px' }}>
        <span style={{ fontSize: '13px', color: '#a1a1aa', display: 'block', marginBottom: '10px', fontWeight: 500 }}>2. Fitur Tambahan (Opsional)</span>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(180px, 1fr))', gap: '10px' }}>
          {Object.keys(FEATURE_LABELS).map((key) => {
            const isActive = features[key];
            return (
              <motion.div
                key={key}
                onClick={() => handleFeatureToggle(key)}
                whileHover={{ scale: 1.02 }}
                whileTap={{ scale: 0.98 }}
                style={{
                  cursor: 'pointer',
                  padding: '12px 16px',
                  borderRadius: '14px',
                  border: '1px solid',
                  borderColor: isActive ? 'rgba(255, 106, 26, 0.5)' : 'rgba(255, 255, 255, 0.04)',
                  background: isActive ? 'rgba(255, 106, 26, 0.03)' : 'rgba(255, 255, 255, 0.01)',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '10px',
                  userSelect: 'none',
                  transition: 'border-color 0.25s, background-color 0.25s'
                }}
              >
                <div style={{
                  width: '16px',
                  height: '16px',
                  borderRadius: '4px',
                  border: '1px solid',
                  borderColor: isActive ? '#ff6a1a' : 'rgba(255, 255, 255, 0.2)',
                  background: isActive ? '#ff6a1a' : 'transparent',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '10px',
                  color: '#fff',
                  flexShrink: 0
                }}>
                  {isActive && '✓'}
                </div>
                <span style={{ fontSize: '12.5px', color: isActive ? '#fff' : '#a1a1aa', fontWeight: 500 }}>{FEATURE_LABELS[key]}</span>
              </motion.div>
            );
          })}
        </div>
      </div>

      {/* 3. Estimasi Investasi & Apply Button */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderTop: '1px solid rgba(255,255,255,0.06)', paddingTop: '20px' }}>
        <div>
          <span style={{ fontSize: '11px', textTransform: 'uppercase', color: '#71717a', display: 'block', letterSpacing: '0.05em', fontWeight: 400 }}>ESTIMASI INVESTASI</span>
          <div style={{ display: 'flex', alignItems: 'baseline', marginTop: '2px', position: 'relative', overflow: 'hidden' }}>
            <span style={{ fontSize: '24px', fontWeight: 300, color: '#ff6a1a', letterSpacing: '-0.02em' }}>
              {formatIDR(displayPrice)}
            </span>
            <AnimatePresence>
              {targetPrice !== displayPrice && (
                <motion.span
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 0.3, y: 0 }}
                  exit={{ opacity: 0, y: -10 }}
                  style={{ fontSize: '12px', marginLeft: '6px', color: '#ff6a1a', fontWeight: 600 }}
                >
                  ⚡
                </motion.span>
              )}
            </AnimatePresence>
          </div>
        </div>
        
        <motion.button
          type="button"
          onClick={handleApply}
          whileHover={{ scale: 1.05, boxShadow: '0 0 15px rgba(255, 106, 26, 0.3)' }}
          whileTap={{ scale: 0.95 }}
          className="btn"
          style={{
            fontSize: '12px',
            padding: '10px 18px',
            margin: 0,
            background: applied ? '#10b981' : 'transparent',
            borderColor: applied ? '#10b981' : 'rgba(255, 106, 26, 0.4)',
            color: '#fff',
            borderRadius: '12px',
            fontWeight: 500,
            transition: 'background-color 0.3s, border-color 0.3s'
          }}
        >
          {applied ? 'Berhasil Diterapkan ✓' : 'Gunakan Estimasi ✓'}
        </motion.button>
      </div>
    </div>
  );
}
