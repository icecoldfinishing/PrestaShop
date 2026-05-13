export type PrestashopQueryParams = Record<string, string | number | boolean>;

export interface PrestashopApiConfig {
  apiKey: string;
  baseUrl: string;
}

export interface UseApiResponseViewerState {
  resource: string;
  resourceId: string;
  paramsText: string;
  formattedResponse: string;
  isLoading: boolean;
  errorMessage: string;
}
