import { useEffect, useState } from "react";
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View } from 'react-native';
import { getPosts } from './src/services/api';

export default function App() {
  const [projectList, setProjectList] = useState([]);

  useEffect(() => {
      getPosts().then((response) =>
          setProjectList(response.data)
      ).catch((error) =>
          console.error(error)
      );

      console.log(projectList);
  }, []);

  return (
    <View style={styles.container}>
      <Text>Open up App.js to start working on your app!</Text>
      

      {projectList.map((project) => (

        <Text>{project.title}</Text>

      ))}
    <StatusBar style="auto" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});
