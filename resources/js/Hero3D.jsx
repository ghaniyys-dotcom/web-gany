import React, { useRef, useState, useEffect } from 'react';
import { Canvas, useFrame } from '@react-three/fiber';
import { Html } from '@react-three/drei';
import * as THREE from 'three';

// Premium SVG Icon Registry for major web development technologies
const svgRegistry = {
  react: (
    <>
      <ellipse cx="12" cy="12" rx="11" ry="4.2" transform="rotate(30 12 12)" />
      <ellipse cx="12" cy="12" rx="11" ry="4.2" transform="rotate(90 12 12)" />
      <ellipse cx="12" cy="12" rx="11" ry="4.2" transform="rotate(150 12 12)" />
      <circle cx="12" cy="12" r="2" fill="currentColor" />
    </>
  ),
  laravel: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 2L4 7v10l8 5 8-5V7l-8-5z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 22V12" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M20 7l-8 5-8-5" />
    </>
  ),
  tailwind: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 14c2-2 5.5-2 7.5 0s2 5.5 0 7.5-5.5 2-7.5 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 10c-2 2-5.5 2-7.5 0s-2-5.5 0-7.5 5.5-2 7.5 0" />
    </>
  ),
  tailwindcss: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 14c2-2 5.5-2 7.5 0s2 5.5 0 7.5-5.5 2-7.5 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 10c-2 2-5.5 2-7.5 0s-2-5.5 0-7.5 5.5-2 7.5 0" />
    </>
  ),
  csstailwind: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 14c2-2 5.5-2 7.5 0s2 5.5 0 7.5-5.5 2-7.5 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 10c-2 2-5.5 2-7.5 0s-2-5.5 0-7.5 5.5-2 7.5 0" />
    </>
  ),
  threejs: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3l10 17H2L12 3z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v17" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L2 20" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L22 20" />
    </>
  ),
  three: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3l10 17H2L12 3z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v17" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L2 20" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 12.5L22 20" />
    </>
  ),
  php: (
    <>
      <ellipse cx="12" cy="12" rx="10" ry="6" strokeWidth="1.5" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M7 9v6 M7 11.5h2a1.5 1.5 0 000-3H7" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M11 9v6 M11 12h2 M13 9v6" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M15 9v6 M15 11.5h2a1.5 1.5 0 000-3H15" />
    </>
  ),
  mysql: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 6c0 1.66 3.58 3 8 3s8-1.34 8-3s-3.58-3-8-3s-8 1.34-8 3z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 6v6c0 1.66 3.58 3 8 3s8-1.34 8-3V6" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M4 12v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6" />
    </>
  ),
  js: (
    <>
      <rect x="3" y="3" width="18" height="18" rx="4" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M9 9v5.5a1.5 1.5 0 01-3 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M13 14c.4.6 1 .9 1.7.9.8 0 1.3-.4 1.3-1s-.4-.7-1.1-.9c-.8-.2-1.4-.4-1.4-1.2s.5-1.1 1.3-1.1c.6 0 1.1.2 1.5.6" />
    </>
  ),
  javascript: (
    <>
      <rect x="3" y="3" width="18" height="18" rx="4" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M9 9v5.5a1.5 1.5 0 01-3 0" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M13 14c.4.6 1 .9 1.7.9.8 0 1.3-.4 1.3-1s-.4-.7-1.1-.9c-.8-.2-1.4-.4-1.4-1.2s.5-1.1 1.3-1.1c.6 0 1.1.2 1.5.6" />
    </>
  ),
  figma: (
    <>
      <path d="M8 5a3 3 0 000 6h3V5H8z" />
      <path d="M11 5a3 3 0 103 3V5h-3z" />
      <path d="M11 11a3 3 0 103-3h-3v3z" />
      <path d="M8 11a3 3 0 103 3v-3H8z" />
      <path d="M8 17a3 3 0 106 0v-3H8a3 3 0 000 3z" />
    </>
  ),
  vuejs: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 21l9-16h-4.5L12 13.5 7.5 5H3l9 16z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 13.5l4-7h-3l-1 2-1-2h-3l4 7z" />
    </>
  ),
  vue: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 21l9-16h-4.5L12 13.5 7.5 5H3l9 16z" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 13.5l4-7h-3l-1 2-1-2h-3l4 7z" />
    </>
  ),
  git: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M18 12c0 1.66-2.69 3-6 3s-6-1.34-6-3" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M12 2v20" />
      <circle cx="12" cy="12" r="3" />
    </>
  ),
  docker: (
    <>
      <rect x="2" y="10" width="4" height="4" rx="1" />
      <rect x="7" y="10" width="4" height="4" rx="1" />
      <rect x="12" y="10" width="4" height="4" rx="1" />
      <rect x="7" y="5" width="4" height="4" rx="1" />
      <path strokeLinecap="round" strokeLinejoin="round" d="M2 16h20c0-4.42-3.58-8-8-8H2v8z" />
    </>
  ),
  livewire: (
    <>
      <path strokeLinecap="round" strokeLinejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
    </>
  ),
};

// Fallback high-tech particle icon
const fallbackSvg = (
  <>
    <path strokeLinecap="round" strokeLinejoin="round" d="M12 3v18M3 12h18M12 7.5l4.5 4.5-4.5 4.5-4.5-4.5z" />
  </>
);

// Individual Tech Node placed on Sphere Constellation (With Z-space big bang explosion and giant hitboxes)
const ConstellationNode = ({ name, skillIndex, totalSkills, targetPos, exploded, isCyberTheme }) => {
  const groupRef = useRef();
  const haloRef = useRef();
  const [hovered, setHovered] = useState(false);
  const explosionStartTime = useRef(null);

  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    
    // Slow rotational swing based on parent sphere rotation
    const baseRadius = 1.6;

    // Spherical coordinates mapping (Golden ratio Fibonacci sphere)
    const phi = Math.acos(-1 + (2 * skillIndex) / totalSkills);
    const theta = Math.sqrt(totalSkills * Math.PI) * phi;
    
    // Orbital coordinates on the 3D globe surface
    let x = baseRadius * Math.cos(theta) * Math.sin(phi);
    let y = baseRadius * Math.sin(theta) * Math.sin(phi);
    let z = baseRadius * Math.cos(phi);

    // Apply slow global compound sphere revolution
    const slowSpeed = time * 0.08;
    const tempX = x * Math.cos(slowSpeed) - z * Math.sin(slowSpeed);
    const tempZ = x * Math.sin(slowSpeed) + z * Math.cos(slowSpeed);
    x = tempX;
    z = tempZ;

    if (groupRef.current) {
      // 💥 Sinematik BIG BANG EXPLOSION - Rushes off-screen completely, then snaps back!
      let entranceMultiplier = 0.01; // tiny core at start
      
      if (exploded) {
        if (explosionStartTime.current === null) {
          explosionStartTime.current = time;
        }
        const elapsed = time - explosionStartTime.current;
        
        if (elapsed < 2.0) {
          // Breathtaking Z-space explosion curve:
          // elapsed=0 to 0.4s: rushes out to scale 22.0 (past camera/off-screen)
          // elapsed=0.4s to 2.0s: recalls and snaps back to 1.0
          if (elapsed < 0.4) {
            const tExp = elapsed / 0.4;
            entranceMultiplier = 0.01 + 21.99 * Math.pow(tExp, 2);
          } else {
            const tRec = (elapsed - 0.4) / 1.6;
            entranceMultiplier = 22.0 + (1.0 - 22.0) * Math.sin(tRec * Math.PI * 0.5);
          }
        } else {
          entranceMultiplier = 1.0;
        }
      }

      const destX = x * entranceMultiplier;
      const destY = y * entranceMultiplier;
      const destZ = z * entranceMultiplier;

      if (hovered) {
        // Magnetic pull towards cursor coordinates in WebGL space
        const targetPX = state.pointer.x * 2.8;
        const targetPY = state.pointer.y * 2.2;
        groupRef.current.position.x += (destX + (targetPX - destX) * 0.28 - groupRef.current.position.x) * 0.15;
        groupRef.current.position.y += (destY + (targetPY - destY) * 0.28 - groupRef.current.position.y) * 0.15;
        groupRef.current.position.z += (destZ - groupRef.current.position.z) * 0.15;
        
        // Rotate outer tech halo ring smoothly
        if (haloRef.current) {
          haloRef.current.rotation.x = time * 0.8;
          haloRef.current.rotation.y = time * 1.2;
          haloRef.current.rotation.z = time * 0.5;
        }
      } else {
        groupRef.current.position.set(destX, destY, destZ);
      }
    }
  });

  const activeColor = isCyberTheme ? '#39ff14' : '#ff5500';

  return (
    <group ref={groupRef}>
      {/* 🛡️ INVISIBLE HIT SHIELD: 2x wider radius target makes hovering completely effortless */}
      <mesh
        onPointerOver={() => setHovered(true)}
        onPointerOut={() => setHovered(false)}
        style={{ cursor: 'pointer' }}
      >
        <sphereGeometry args={[0.85, 16, 16]} />
        <meshBasicMaterial visible={false} />
      </mesh>

      {/* 🔮 Glowing Constellation vertex node - scales up by 2.8x on hover */}
      <mesh scale={hovered ? [2.8, 2.8, 2.8] : [1.0, 1.0, 1.0]}>
        <sphereGeometry args={[0.07, 16, 16]} />
        <meshBasicMaterial 
          color={hovered ? activeColor : 'rgba(255, 255, 255, 0.45)'} 
          transparent
          opacity={hovered ? 1.0 : 0.45}
        />
      </mesh>

      {/* Orbit node outer neural halo rings - high-tech revolving wireframe */}
      {hovered && (
        <mesh ref={haloRef}>
          <dodecahedronGeometry args={[0.22, 0]} />
          <meshBasicMaterial color={activeColor} wireframe transparent opacity={0.45} />
        </mesh>
      )}

      {/* Holographic Typographic Info Card - styled with Space Grotesk, border glow & backdrop blur */}
      <Html center distanceFactor={7.5} style={{ pointerEvents: hovered ? 'auto' : 'none' }}>
        <div
          onPointerOver={() => setHovered(true)}
          onPointerOut={() => setHovered(false)}
          className={`flex items-center gap-2 select-none pointer-events-auto transition-all duration-500 transform ${
            hovered 
              ? 'opacity-100 scale-100 translate-x-4 translate-y-[-12px]' 
              : 'opacity-0 scale-75 translate-x-0 pointer-events-none'
          }`}
          style={{
            fontFamily: "'Space Grotesk', sans-serif",
          }}
        >
          <div className="flex items-center gap-1.5" style={{ position: 'relative' }}>
            <div className="w-2 h-2 rounded-full animate-ping" style={{ backgroundColor: activeColor, position: 'absolute' }}></div>
            <div className="w-2 h-2 rounded-full" style={{ backgroundColor: activeColor, boxShadow: `0 0 10px ${activeColor}` }}></div>
            <div className="w-8 h-px" style={{ backgroundColor: activeColor, opacity: 0.65 }}></div>
          </div>

          <span 
            className="text-[11px] font-bold tracking-wider uppercase text-white whitespace-nowrap bg-black/90 px-3 py-2 rounded-md border-l-2"
            style={{ 
              borderLeftColor: activeColor,
              borderTopColor: 'rgba(255,255,255,0.06)',
              borderRightColor: 'rgba(255,255,255,0.06)',
              borderBottomColor: 'rgba(255,255,255,0.06)',
              borderStyle: 'solid',
              borderWidth: '1px',
              borderLeftWidth: '3px',
              backdropFilter: 'blur(12px)',
              boxShadow: hovered ? `0 8px 32px rgba(0,0,0,0.85), 0 0 12px ${activeColor}33` : '0 4px 10px rgba(0,0,0,0.5)'
            }}
          >
            {name}
          </span>
        </div>
      </Html>
    </group>
  );
};

// Neural globe connecting wireframe grid
const GlobeGrid = ({ isCyberTheme, exploded }) => {
  const meshRef = useRef();
  const explosionStartTime = useRef(null);

  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    
    // Slow rotational velocity matching logos
    const rotSpeed = time * 0.08;
    if (meshRef.current) {
      meshRef.current.rotation.y = rotSpeed;
    }

    // 💥 BIG BANG IGNITION SCALE: Starts at scale 0, ignites at peak explosion (0.4s), snaps to 1.0!
    let entranceScale = 1.0;
    if (exploded) {
      if (explosionStartTime.current === null) {
        explosionStartTime.current = time;
      }
      const elapsed = time - explosionStartTime.current;
      
      if (elapsed < 2.0) {
        if (elapsed < 0.4) {
          entranceScale = 0; // completely invisible/imploded at start
        } else {
          const t = (elapsed - 0.4) / 1.6;
          // Smooth spring scale ignition
          entranceScale = Math.sin(t * Math.PI * 0.5) * 1.25 * (1 - t) + t * 1.0;
        }
      } else {
        entranceScale = 1.0;
      }
    } else {
      entranceScale = 0; // wait for click
    }

    const baseScale = 1.0 + Math.sin(time * 0.85) * 0.015; // compound breathing
    const finalScale = baseScale * entranceScale;
    
    if (meshRef.current) {
      meshRef.current.scale.set(finalScale, finalScale, finalScale);
    }
  });

  const activeColor = isCyberTheme ? '#39ff14' : '#ff5500';

  return (
    <mesh ref={meshRef}>
      <sphereGeometry args={[1.5, 18, 18]} />
      <meshBasicMaterial
        color={activeColor}
        wireframe
        transparent
        opacity={0.12}
        depthWrite={false}
      />
    </mesh>
  );
};

// Generative point cloud quantum particle swarm (650+ layered stars) + Gravitational Sway
const QuantumParticleSwarm = ({ isCyberTheme, exploded }) => {
  const pointsRef = useRef();
  const particleCount = 650;
  const explosionStartTime = useRef(null);

  // Compute points shell layout once
  const [positions] = React.useMemo(() => {
    const pos = new Float32Array(particleCount * 3);
    for (let i = 0; i < particleCount; i++) {
      const u = Math.random();
      const v = Math.random();
      const theta = u * 2.0 * Math.PI;
      const phi = Math.acos(2.0 * v - 1.0);
      const r = 1.25 + Math.random() * 0.5; // spherical layered shell

      pos[i * 3] = r * Math.sin(phi) * Math.cos(theta);
      pos[i * 3 + 1] = r * Math.sin(phi) * Math.sin(theta);
      pos[i * 3 + 2] = r * Math.cos(phi);
    }
    return [pos];
  }, []);

  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    const pointerX = state.pointer.x * 2.8;
    const pointerY = state.pointer.y * 2.2;

    if (pointsRef.current) {
      pointsRef.current.rotation.y = time * 0.04;
      pointsRef.current.rotation.x = time * 0.025;

      // 🌪️ Dynamic cursor gravitational wind sway
      pointsRef.current.position.x += (pointerX * 0.18 - pointsRef.current.position.x) * 0.05;
      pointsRef.current.position.y += (pointerY * 0.18 - pointsRef.current.position.y) * 0.05;
      
      const baseScale = 1.0 + Math.sin(time * 0.95) * 0.035;

      // 💥 Sinematik BIG BANG EXPLOSION - Rushes completely off-screen, past camera view!
      let entranceMultiplier = 0.01; // tight central pack
      
      if (exploded) {
        if (explosionStartTime.current === null) {
          explosionStartTime.current = time;
        }
        const elapsed = time - explosionStartTime.current;
        
        if (elapsed < 2.0) {
          if (elapsed < 0.4) {
            const tExp = elapsed / 0.4;
            // Explosive burst to scale 22.0! Flies past camera coordinates
            entranceMultiplier = 0.01 + 21.99 * Math.pow(tExp, 2);
          } else {
            const tRec = (elapsed - 0.4) / 1.6;
            // Magnetic recall pulling back to globe shell
            entranceMultiplier = 22.0 + (1.0 - 22.0) * Math.sin(tRec * Math.PI * 0.5);
          }
        } else {
          entranceMultiplier = 1.0;
        }
      }

      const finalScale = baseScale * entranceMultiplier;
      pointsRef.current.scale.set(finalScale, finalScale, finalScale);
    }
  });

  const activeColor = isCyberTheme ? '#39ff14' : '#ff5500';

  return (
    <points ref={pointsRef}>
      <bufferGeometry>
        <bufferAttribute
          attach="attributes-position"
          args={[positions, 3]}
        />
      </bufferGeometry>
      <pointsMaterial
        color={activeColor}
        size={0.045}
        sizeAttenuation={true}
        transparent={true}
        opacity={0.65}
        depthWrite={false}
        blending={THREE.AdditiveBlending}
      />
    </points>
  );
};

// Double-Nested Contrasting Core CPU Reactor (Dodecahedron & Glass Icosahedron)
const CentralObject = ({ isCyberTheme, exploded }) => {
  const outerRef = useRef();
  const innerRef = useRef();
  const explosionStartTime = useRef(null);

  useFrame((state) => {
    const time = state.clock.getElapsedTime();
    
    const rotX = time * 0.12;
    const rotY = time * 0.16;
    const innerRotX = -time * 0.28;
    const innerRotY = -time * 0.35;
    
    if (outerRef.current) {
      outerRef.current.rotation.x = rotX;
      outerRef.current.rotation.y = rotY;
    }
    if (innerRef.current) {
      innerRef.current.rotation.x = innerRotX;
      innerRef.current.rotation.y = innerRotY;
    }

    // 💥 BIG BANG IGNITION SCALE: Stays 0, bursts to life, snaps to 0.82!
    let entranceScale = 1.0;
    if (exploded) {
      if (explosionStartTime.current === null) {
        explosionStartTime.current = time;
      }
      const elapsed = time - explosionStartTime.current;
      
      if (elapsed < 2.0) {
        if (elapsed < 0.4) {
          entranceScale = 0;
        } else {
          const t = (elapsed - 0.4) / 1.6;
          // Smooth bounce ignite
          entranceScale = Math.sin(t * Math.PI * 0.5) * 1.25 * (1 - t) + t * 1.0;
        }
      } else {
        entranceScale = 1.0;
      }
    } else {
      entranceScale = 0; // wait
    }

    const baseScale = 0.82 + Math.sin(time * 1.8) * 0.04;
    const finalScale = baseScale * entranceScale;

    if (outerRef.current) {
      outerRef.current.scale.set(finalScale, finalScale, finalScale);
    }
    if (innerRef.current) {
      const innerScale = finalScale * 0.52;
      innerRef.current.scale.set(innerScale, innerScale, innerScale);
    }
  });

  const activeColor = isCyberTheme ? '#39ff14' : '#ff3d00';

  return (
    <group>
      {/* Outer Wireframe CPU Ring */}
      <mesh ref={outerRef}>
        <dodecahedronGeometry args={[1, 1]} />
        <meshBasicMaterial
          color={activeColor}
          wireframe
          transparent
          opacity={0.35}
        />
      </mesh>

      {/* Inner Crystalline Physical Glass Mutator */}
      <mesh ref={innerRef}>
        <icosahedronGeometry args={[1, 0]} />
        <meshPhysicalMaterial
          color="#060606"
          emissive={activeColor}
          emissiveIntensity={isCyberTheme ? 0.25 : 0.15}
          roughness={0.08}
          metalness={0.85}
          transmission={0.95}
          thickness={1.6}
          transparent
          opacity={0.92}
        />
      </mesh>
    </group>
  );
};

// Mouse Parallax Wrapper Scene
const ParallaxGroup = ({ children }) => {
  const groupRef = useRef();

  useFrame((state) => {
    const targetX = -state.pointer.x * 0.28;
    const targetY = state.pointer.y * 0.28;

    if (groupRef.current) {
      groupRef.current.rotation.y += (targetX - groupRef.current.rotation.y) * 0.06;
      groupRef.current.rotation.x += (targetY - groupRef.current.rotation.x) * 0.06;

      // 📱 Real-time mathematical responsiveness: scale down scene automatically on narrow/mobile viewports
      const responsiveScale = Math.min(1.0, state.viewport.width / 5.2);
      groupRef.current.scale.set(responsiveScale, responsiveScale, responsiveScale);
    }
  });

  return <group ref={groupRef}>{children}</group>;
};

// Main Export Component
export default function Hero3D({ initialSkills }) {
  const [isCyberTheme, setIsCyberTheme] = useState(false);
  const [exploded, setExploded] = useState(false);

  // Sync color variables with theme classes
  useEffect(() => {
    const checkTheme = () => {
      setIsCyberTheme(document.documentElement.classList.contains('theme-cyber'));
    };
    checkTheme();
    const observer = new MutationObserver(checkTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    return () => observer.disconnect();
  }, []);

  // Set up the dynamic hardware-accelerated mounting transition
  useEffect(() => {
    const parent = document.getElementById('hero-3d-hologram');
    if (parent) {
      const timer = setTimeout(() => {
        parent.classList.add('visible');
      }, 30);
      return () => clearTimeout(timer);
    }
  }, []);

  // 💥 Ignite Big Bang Explosion precisely on curtain opening OR on direct load!
  useEffect(() => {
    let isIntroActive = false;
    try {
      isIntroActive = typeof window !== 'undefined' && 
                      typeof document !== 'undefined' && 
                      !!document.getElementById('intro-overlay');
    } catch (e) {
      isIntroActive = false;
    }

    if (isIntroActive) {
      const handleDismiss = () => {
        clearTimeout(fallbackTimer);
        setTimeout(() => {
          setExploded(true);
          // 🔊 Play elegant cosmic Big Bang explosion sound!
          if (window.FluxoraAudio && typeof window.FluxoraAudio.playCosmicExplosion === 'function') {
            window.FluxoraAudio.playCosmicExplosion();
          }
          // 🔊 Play elegant cosmic contraction/recall sound exactly at explosion peak (400ms)
          setTimeout(() => {
            if (window.FluxoraAudio && typeof window.FluxoraAudio.playCosmicRecall === 'function') {
              window.FluxoraAudio.playCosmicRecall();
            }
          }, 400);
        }, 500); // 500ms delay matches the curtains parting peak perfectly!
      };
      window.addEventListener('intro-dismissed', handleDismiss);

      // Fallback: trigger after 4.5 seconds anyway if the intro overlay is hidden/broken/stuck
      const fallbackTimer = setTimeout(() => {
        window.removeEventListener('intro-dismissed', handleDismiss);
        setExploded(true);
      }, 4500);

      return () => {
        window.removeEventListener('intro-dismissed', handleDismiss);
        clearTimeout(fallbackTimer);
      };
    } else {
      // Intro overlay is already seen. Trigger on FIRST user interaction (mousemove, scroll, click, touch)
      // to bypass browser autoplay blocks and ensure the explosion sound plays 100% reliably!
      let triggered = false;
      const triggerExplosion = () => {
        if (triggered) return;
        triggered = true;

        window.removeEventListener('mousemove', triggerExplosion);
        window.removeEventListener('scroll', triggerExplosion);
        window.removeEventListener('click', triggerExplosion);
        window.removeEventListener('touchstart', triggerExplosion);

        // Resume AudioContext in case it's suspended
        if (window.FluxoraAudio && window.FluxoraAudio.ctx && window.FluxoraAudio.ctx.state === 'suspended') {
          window.FluxoraAudio.ctx.resume();
        }

        setTimeout(() => {
          setExploded(true);
          // 🔊 Play elegant cosmic Big Bang explosion sound!
          if (window.FluxoraAudio && typeof window.FluxoraAudio.playCosmicExplosion === 'function') {
            window.FluxoraAudio.playCosmicExplosion();
          }
          // 🔊 Play elegant cosmic contraction/recall sound exactly at explosion peak (400ms)
          setTimeout(() => {
            if (window.FluxoraAudio && typeof window.FluxoraAudio.playCosmicRecall === 'function') {
              window.FluxoraAudio.playCosmicRecall();
            }
          }, 400);
        }, 50); // slight 50ms buffer for tactile reaction smoothness
      };

      window.addEventListener('mousemove', triggerExplosion, { passive: true });
      window.addEventListener('scroll', triggerExplosion, { passive: true });
      window.addEventListener('click', triggerExplosion, { passive: true });
      window.addEventListener('touchstart', triggerExplosion, { passive: true });

      // Fallback: trigger after 2.2s anyway if the user is completely idle
      const fallbackTimer = setTimeout(triggerExplosion, 2200);

      return () => {
        window.removeEventListener('mousemove', triggerExplosion);
        window.removeEventListener('scroll', triggerExplosion);
        window.removeEventListener('click', triggerExplosion);
        window.removeEventListener('touchstart', triggerExplosion);
        clearTimeout(fallbackTimer);
      };
    }
  }, []);

  // Default skills (fallback if database has none selected) — 15 skills matching DB
  const defaultSkills = [
    { name: 'Laravel',       radius: 2.0, yOffset: -0.2 },
    { name: 'PHP',           radius: 2.2, yOffset:  0.2 },
    { name: 'Vue.js',        radius: 2.1, yOffset:  0.3 },
    { name: 'JavaScript',    radius: 2.3, yOffset: -0.3 },
    { name: 'MySQL',         radius: 2.0, yOffset:  0.4 },
    { name: 'PostgreSQL',    radius: 2.1, yOffset: -0.4 },
    { name: 'REST APIs',     radius: 2.2, yOffset:  0.1 },
    { name: 'Docker',        radius: 2.0, yOffset: -0.1 },
    { name: 'CSS / Tailwind',radius: 2.3, yOffset:  0.5 },
    { name: 'Git',           radius: 2.1, yOffset: -0.5 },
    { name: 'UI / UX',       radius: 2.0, yOffset:  0.25 },
    { name: 'Livewire',      radius: 2.2, yOffset: -0.25 },
    { name: 'React',         radius: 2.1, yOffset:  0.35 },
    { name: 'Three.js',      radius: 2.3, yOffset: -0.35 },
    { name: 'Figma',         radius: 2.0, yOffset:  0.45 },
  ];

  const rawSkills = defaultSkills;
  
  const techLogos = rawSkills.map((skill, index) => {
    const normalizedName = skill.name.toLowerCase().replace(/[^a-z0-9]/g, '');
    const svgPath = svgRegistry[normalizedName] || fallbackSvg;
    const radius = skill.radius || (2.0 + (index % 4) * 0.1);
    const yOffset = skill.yOffset !== undefined ? skill.yOffset : ((index % 2 === 0 ? 0.25 : -0.3) + (index % 3) * 0.08);

    return {
      name: skill.name,
      svgPath,
      radius,
      yOffset
    };
  });

  return (
    <div className="w-full h-full relative flex items-center justify-center">
      <Canvas
        camera={{ position: [0, 0, 5.2], fov: 60 }}
        style={{
          width: '100%',
          height: '100%',
          position: 'absolute',
          top: 0,
          left: 0,
          pointerEvents: 'auto',
          overflow: 'visible', /* BORDERLESS EXPLOSION! */
        }}
        gl={{ antialias: true, alpha: true }}
      >
        <ambientLight intensity={0.5} />
        <directionalLight position={[5, 10, 5]} intensity={1.5} />
        <pointLight position={[-10, -10, -10]} intensity={0.8} />

        <ParallaxGroup>
          {/* Central nested crystalline core reaktor CPU */}
          <CentralObject isCyberTheme={isCyberTheme} exploded={exploded} />

          {/* Neural globe network connecting grid */}
          <GlobeGrid isCyberTheme={isCyberTheme} exploded={exploded} />

          {/* Particle cloud quantum stars swarm (responds to mouse gravity wind) */}
          <QuantumParticleSwarm isCyberTheme={isCyberTheme} exploded={exploded} />

          {/* Tilted spherical constellation keahlian nodes */}
          {techLogos.map((logo, index) => (
            <ConstellationNode
              key={logo.name}
              name={logo.name}
              skillIndex={index}
              totalSkills={techLogos.length}
              exploded={exploded}
              isCyberTheme={isCyberTheme}
            />
          ))}
        </ParallaxGroup>
      </Canvas>
    </div>
  );
}
