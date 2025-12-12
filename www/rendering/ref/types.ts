
export interface MaterialSlot {
  id: string;
  label: string;
  file: File | null;
  previewUrl: string | null;
  mode: MaterialMode;
  presetColor: PresetColor;
  presetType: PresetType;
}

export interface GenerationConfig {
  layoutImage: File | null;
  doorImage: File | null;
  panelMaterials: MaterialSlot[];
}

export type FloorMode = 'upload' | 'preset';
export type FloorPreset = 'deco-tile' | 'marble';

export enum AppState {
  IDLE = 'IDLE',
  GENERATING = 'GENERATING',
  SUCCESS = 'SUCCESS',
  ERROR = 'ERROR'
}

export type MaterialMode = 'upload' | 'preset';
export type PresetType = 'hairline' | 'mirror' | 'vibration' | 'bead';
export type PresetColor = 'silver' | 'gold' | 'bronze' | 'black';
