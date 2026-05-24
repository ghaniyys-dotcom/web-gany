import React from 'react';
import { motion } from 'framer-motion';

export default function AboutContent({ eyebrow, heading, description, signature }) {
  // Container animations
  const containerVariants = {
    hidden: {},
    visible: {
      transition: {
        staggerChildren: 0.15,
        delayChildren: 0.05,
      },
    },
  };

  // Item animations using Custom Emil Kowalski ease
  const itemVariants = {
    hidden: {
      opacity: 0,
      y: 35,
    },
    visible: {
      opacity: 1,
      y: 0,
      transition: {
        duration: 1.0,
        ease: [0.32, 0.72, 0, 1], // Emil Kowalski custom bezier
      },
    },
  };

  const textLinesVariants = {
    hidden: {},
    visible: {
      transition: {
        staggerChildren: 0.02,
      },
    },
  };

  const wordRevealVariants = {
    hidden: {
      y: '100%',
      opacity: 0,
    },
    visible: {
      y: 0,
      opacity: 1,
      transition: {
        duration: 0.85,
        ease: [0.32, 0.72, 0, 1],
      },
    },
  };

  return (
    <motion.div
      className="founder-content"
      variants={containerVariants}
      initial="hidden"
      whileInView="visible"
      viewport={{ once: true, margin: '-10% 0px' }}
      style={{
        display: 'flex',
        flexDirection: 'column',
        gap: '24px',
      }}
    >
      {/* 1. Eyebrow animation */}
      <motion.span
        className="founder-eyebrow"
        variants={itemVariants}
        style={{
          textTransform: 'uppercase',
          letterSpacing: '0.12em',
          color: '#ff6a1a', // Fiery orange/warm accent
          fontWeight: 600,
          fontSize: '13px',
        }}
      >
        {eyebrow}
      </motion.span>

      {/* 2. Heading with staggered line/word mask reveal */}
      <div style={{ overflow: 'hidden' }}>
        <motion.h2
          className="founder-heading"
          variants={textLinesVariants}
          style={{
            fontSize: 'clamp(36px, 4.5vw, 56px)',
            fontWeight: 300,
            letterSpacing: '-0.02em', // Tight letter-spacing for premium feel
            lineHeight: 1.15,
            color: '#fff',
            margin: 0,
            display: 'flex',
            flexWrap: 'wrap',
            columnGap: '0.25em',
            rowGap: '0.1em',
          }}
        >
          {heading ? heading.split(' ').map((word, idx) => (
            <span
              key={idx}
              style={{
                display: 'inline-block',
                overflow: 'hidden',
                paddingBottom: '0.05em',
                marginBottom: '-0.05em',
              }}
            >
              <motion.span
                variants={wordRevealVariants}
                style={{ display: 'inline-block' }}
              >
                {word}
              </motion.span>
            </span>
          )) : null}
        </motion.h2>
      </div>

      {/* 3. Description animation */}
      <motion.p
        className="founder-description"
        variants={itemVariants}
        style={{
          fontSize: '17px',
          lineHeight: '1.75', // Relaxed line-height for readability
          color: '#a1a1aa',
          margin: 0,
          fontWeight: 400,
        }}
      >
        {description}
      </motion.p>

      {/* 4. Signature animation */}
      {signature && (
        <motion.div
          className="founder-signature-box"
          variants={itemVariants}
          style={{
            marginTop: '16px',
            opacity: 0.9,
          }}
        >
          <motion.img
            src={signature}
            alt="Digital Signature"
            className="founder-signature"
            whileHover={{ scale: 1.05 }}
            transition={{ type: 'spring', stiffness: 200, damping: 15 }}
            style={{
              maxHeight: '65px',
              objectFit: 'contain',
              filter: 'brightness(1.2) contrast(1.1)',
            }}
          />
        </motion.div>
      )}
    </motion.div>
  );
}
