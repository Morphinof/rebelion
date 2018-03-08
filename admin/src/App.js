import React from 'react';
import { HydraAdmin } from '@api-platform/admin';

//{process.env.REACT_APP_API_ENTRYPOINT}
export default () => <HydraAdmin entrypoint="http://localhost:8080" />;
